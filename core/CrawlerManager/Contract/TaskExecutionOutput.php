<?php

namespace CrawlerManager\Contract;

readonly class TaskExecutionOutput
{
    public function __construct(
        public array $successIds,
        public array $skippedIds,
        public array $errorIds,
    )
    {

    }
}

