<?php

namespace JobAccess\Contract\Task;

readonly class TaskExecuteValidationOutput
{
    public function __construct(
        public bool $isValid,
        public bool $isSkipped,
    )
    {

    }
}
