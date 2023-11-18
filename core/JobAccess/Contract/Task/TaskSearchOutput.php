<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataModel\CrawlJobTask;

readonly class TaskSearchOutput
{
    /**
     * @param CrawlJobTask[] $items
     * @param int $totalCount
     */
    public function __construct(
        public array $items,
        public int   $totalCount,
    )
    {

    }
}
