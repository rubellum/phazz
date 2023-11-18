<?php

namespace JobAccess\Contract\Job;

use JobAccess\DataModel\JobSearchCondition;

readonly class JobSearchInput
{
    public function __construct(
        public JobSearchCondition $condition,
    )
    {

    }
}
