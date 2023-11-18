<?php

namespace JobAccess\Contract\Job;

use JobAccess\DataModel\CrawlJob;

readonly class JobSearchOutput
{
    /**
     * @param CrawlJob[] $items
     * @param int $totalCount
     */
    public function __construct(
        public array $items,
        public int   $totalCount,
    )
    {

    }
}
