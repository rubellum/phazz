<?php

namespace JobAccess\Contract\Task;

readonly class TaskRegistrationInput
{
    public function __construct(
        public int $jobId,
    )
    {

    }
}
