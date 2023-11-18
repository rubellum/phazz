<?php

namespace JobAccess\Contract\Job;

readonly class JobRequestValidationOutput
{
    public function __construct(
        public int $id,
    )
    {

    }
}
