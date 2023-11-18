<?php

namespace JobAccess\Contract\Job;

use JobAccess\DataAccess\CrawlJobAccess;

readonly class JobSearch
{

    public function __construct(
        private CrawlJobAccess $jobSearch,
    )
    {
    }

    public function search(JobSearchInput $input): JobSearchOutput
    {
        return $this->jobSearch->search($input->condition);
    }
}
