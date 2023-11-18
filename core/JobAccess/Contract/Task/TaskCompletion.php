<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataAccess\CrawlJobTaskAccess;
use JobAccess\Exception\JobAccessException;

readonly class TaskCompletion
{
    public function __construct(
        private CrawlJobTaskAccess $jobTaskAccess,
    )
    {

    }

    /**
     * @param TaskCompletionInput $input
     * @return TaskCompletionOutput
     * @throws JobAccessException when the job task is not in the correct state
     */
    public function completion(TaskCompletionInput $input): TaskCompletionOutput
    {
        $this->jobTaskAccess->changeState($input->taskId, $input->state);

        return new TaskCompletionOutput(
            newState: $input->state,
        );
    }
}
