<?php

namespace JobAccess\Contract\Job;

readonly class JobSavingInput
{
    public function __construct(
        public ?int   $id,
        public string $name,
        public string $url,
        public string $type,
        public array  $operation,
    )
    {

    }
}
