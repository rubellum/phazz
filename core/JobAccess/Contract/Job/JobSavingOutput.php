<?php

namespace JobAccess\Contract\Job;

readonly class JobSavingOutput
{
    public function __construct(
        public int $id,
    )
    {

    }
}
