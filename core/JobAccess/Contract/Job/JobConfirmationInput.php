<?php

namespace JobAccess\Contract\Job;

readonly class JobConfirmationInput
{
    public function __construct(
        public int $id,
    )
    {

    }
}
