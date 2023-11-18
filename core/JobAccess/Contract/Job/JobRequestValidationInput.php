<?php

namespace JobAccess\Contract\Job;

readonly class JobRequestValidationInput
{
    public function __construct(
        public string $name,
        public string $url,
        public array $operation,
    )
    {

    }
}
