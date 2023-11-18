<?php

namespace CrawlerManager\Contract;

readonly class TaskRegistrationOutput
{
    public function __construct(
        public int   $taskId,
        public array $errors,
    )
    {

    }
}

