<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataModel\CrawlJobTaskState;

readonly class TaskCompletionInput
{
    public function __construct(
        public int               $taskId,
        public CrawlJobTaskState $state,
    )
    {

    }
}
