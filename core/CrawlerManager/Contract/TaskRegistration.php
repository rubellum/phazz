<?php

namespace CrawlerManager\Contract;

use JobAccess\Contract\Task\TaskRegistration as JobTaskRegistration;
use JobAccess\Contract\Task\TaskRegistrationInput as JobTaskRegistrationInput;

readonly class TaskRegistration
{
    public function __construct(
        private JobTaskRegistration $taskRegistration,
    )
    {

    }

    public function execute(TaskRegistrationInput $input): TaskRegistrationOutput
    {
        $taskId = $this->taskRegistration->register(
            new JobTaskRegistrationInput(
                jobId: $input->jobId,
            ),
        )->taskId;

        return new TaskRegistrationOutput(
            taskId: $taskId,
            errors: [],
        );
    }
}
