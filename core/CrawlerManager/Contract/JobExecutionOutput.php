<?php

namespace CrawlerManager\Contract;

readonly class JobExecutionOutput
{
    public function __construct(
        public array $successIds = [],
        public array $skippedIds = [],
        public array $errorIds = [],
    )
    {

    }
}

