<?php

namespace JobAccess\Contract\Task;

readonly class TaskConfirmationInput
{
    public function __construct(
        public int $id,
    )
    {

    }
}
