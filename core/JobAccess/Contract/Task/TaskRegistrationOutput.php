<?php

namespace JobAccess\Contract\Task;

readonly class TaskRegistrationOutput
{
    public function __construct(
        public int $taskId,
    )
    {

    }
}
