<?php

namespace JobAccess\Contract\Task;

readonly class TaskSearchInput
{
    public function __construct(
        public ?int $jobId = null,
        public ?int $jobTaskId = null,
        public ?int $page = null,
        public ?int $perPage = null,
    )
    {

    }
}
