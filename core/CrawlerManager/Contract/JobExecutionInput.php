<?php

namespace CrawlerManager\Contract;

readonly class JobExecutionInput
{
    public function __construct(
        public int $taskId,
    )
    {

    }
}
