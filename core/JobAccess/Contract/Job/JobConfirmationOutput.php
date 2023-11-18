<?php

namespace JobAccess\Contract\Job;

use JobAccess\DataModel\CrawlJob;

readonly class JobConfirmationOutput
{
    public function __construct(
        public CrawlJob $job,
    )
    {

    }
}
