<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataModel\CrawlJobTask;

readonly class TaskExecutableRetrieveOutput
{
    /**
     * @param CrawlJobTask[] $tasks
     */
    public function __construct(
        public array $tasks,
    )
    {

    }
}
