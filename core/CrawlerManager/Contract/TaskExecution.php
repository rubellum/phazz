<?php

namespace CrawlerManager\Contract;

use CollectEngine\Contract\Collect;
use CollectEngine\Contract\CollectInput;
use ContentAccess\Contract\ContentStore;
use ContentAccess\Contract\ContentStoreInput;
use JobAccess\Contract\Task\TaskCompletion;
use JobAccess\Contract\Task\TaskCompletionInput;
use JobAccess\Contract\Task\TaskExecutableRetrieve;
use JobAccess\Contract\Task\TaskExecutableRetrieveInput;
use JobAccess\Contract\Task\TaskExecuteValidation;
use JobAccess\Contract\Task\TaskExecuteValidationInput;
use JobAccess\DataModel\CrawlJobTaskState;
use JobAccess\Exception\JobAccessException;
use LoggingService\Contract\LoggingService;

readonly class TaskExecution
{
    public function __construct(
        private TaskExecutableRetrieve $taskExecutableRetrieve,
        private TaskExecuteValidation  $taskExecuteValidation,
        private Collect                $collect,
        private ContentStore           $store,
        private TaskCompletion         $taskCompletion,
        private LoggingService         $logging,
    )
    {

    }

    /**
     * @param TaskExecutionInput $input
     * @return TaskExecutionOutput
     * @throws JobAccessException
     */
    public function execute(TaskExecutionInput $input): TaskExecutionOutput
    {
        $tasks = $this->taskExecutableRetrieve->retrieve(
            new TaskExecutableRetrieveInput(),
        )->tasks;

        $result = [
            'successIds' => [],
            'skippedIds' => [],
            'errorIds' => [],
        ];

        foreach ($tasks as $task) {
            $validationOutput = $this->taskExecuteValidation->validate(
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
                $result['errorIds'][] = $task->id;
                continue;
            } elseif ($validationOutput->isSkipped) {
                $this->logging->info(
                    message: 'Task skipped',
                    context: [
                        'taskId' => $task->id,
                    ]);
                $result['skippedIds'][] = $task->id;
                continue;
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

            $result['successIds'][] = $task->id;
        }

        return new TaskExecutionOutput(
            successIds: $result['successIds'],
            skippedIds: $result['skippedIds'],
            errorIds: $result['errorIds'],
        );
    }
}
