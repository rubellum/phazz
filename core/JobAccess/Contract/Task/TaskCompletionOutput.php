<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataModel\CrawlJobTaskState;

readonly class TaskCompletionOutput
{
    public function __construct(
        public CrawlJobTaskState $newState,
    )
    {

    }
}
