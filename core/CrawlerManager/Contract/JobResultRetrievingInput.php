<?php

namespace CrawlerManager\Contract;

readonly class JobResultRetrievingInput
{
    public function __construct(
        public int $taskId,
    )
    {

    }
}
