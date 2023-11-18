<?php

namespace CrawlerManager\Contract;

use CollectEngine\Contract\Collect;
use CollectEngine\Contract\CollectInput;
use ContentAccess\Contract\ContentStore;
use ContentAccess\Contract\ContentStoreInput;
use JobAccess\Contract\Task\TaskCompletion;
use JobAccess\Contract\Task\TaskCompletionInput;
use JobAccess\Contract\Task\TaskConfirmation;
use JobAccess\Contract\Task\TaskConfirmationInput;
use JobAccess\Contract\Task\TaskExecuteValidation;
use JobAccess\Contract\Task\TaskExecuteValidationInput;
use JobAccess\DataModel\CrawlJobTaskState;
use JobAccess\Exception\JobAccessException;
use LoggingService\Contract\LoggingService;

readonly class JobExecution
{
    public function __construct(
        private TaskConfirmation      $taskConfirmation,
        private TaskExecuteValidation $executeValidation,
        private Collect               $collect,
        private ContentStore          $store,
        private TaskCompletion        $taskCompletion,
        private LoggingService        $logging,
    )
    {

    }

    /**
     * @param JobExecutionInput $input
     * @return JobExecutionOutput
     * @throws JobAccessException
     */
    public function execute(JobExecutionInput $input): JobExecutionOutput
    {
        $task = $this->taskConfirmation->confirm(
            new TaskConfirmationInput(
                id: $input->taskId,
            ),
        )->task;
        $validationOutput = $this->executeValidation->validate(
            new TaskExecuteValidationInput(
                task: $task,
            ),
        );
        if ($validationOutput->isValid === false) {
            $this->logging->error(
                message: 'Task validation failed',
                context: [
                    'taskId' => $task->id,
                ]);
            return new JobExecutionOutput(
                errorIds: [$task->id],
            );
        } elseif ($validationOutput->isSkipped) {
            $this->logging->info(
                message: 'Task skipped',
                context: [
                    'taskId' => $task->id,
                ]);
            return new JobExecutionOutput(
                skippedIds: [$task->id],
            );
        }
        $collectOutput = $this->collect->collect(
            new CollectInput(
                url: $task->url,
                operation: $task->operation,
            ),
        );
        $storeOutput = $this->store->store(
            new ContentStoreInput(
                path: $task->path,
                contentFilePath: $collectOutput->contentFilePath,
            ),
        );
        $completionOutput = $this->taskCompletion->completion(
            new TaskCompletionInput(
                taskId: $task->id,
                state: CrawlJobTaskState::SUCCESS,
            ),
        );
        return new JobExecutionOutput(
            successIds: [$task->id],
        );
    }
}
