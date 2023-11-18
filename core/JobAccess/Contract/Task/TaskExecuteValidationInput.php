<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataModel\CrawlJobTask;

readonly class TaskExecuteValidationInput
{
    public function __construct(
        public CrawlJobTask $task,
    )
    {

    }
}
