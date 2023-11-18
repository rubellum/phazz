<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataAccess\CrawlJobAccess;
use JobAccess\DataAccess\CrawlJobTaskAccess;
use JobAccess\Exception\Exception;

readonly class TaskRegistration
{
    public function __construct(
        private CrawlJobAccess     $jobAccess,
        private CrawlJobTaskAccess $taskAccess,
    )
    {

    }

    /**
     * @param TaskRegistrationInput $input
     * @return TaskRegistrationOutput
     * @throws Exception
     */
    public function register(TaskRegistrationInput $input): TaskRegistrationOutput
    {
        $job = $this->jobAccess->findById($input->jobId);

        $result = $this->taskAccess->save(
            $job->registerFirstTask(),
        );

        return new TaskRegistrationOutput(
            taskId: $result['id'],
        );
    }
}
