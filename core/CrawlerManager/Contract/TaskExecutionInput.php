<?php

namespace CrawlerManager\Contract;

readonly class TaskExecutionInput
{
    public function __construct(
        public int $taskId,
    )
    {

    }
}
