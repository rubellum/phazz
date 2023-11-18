<?php

namespace JobAccess\DataModel;

readonly class CrawlJobTaskDraft
{
    public function __construct(
        public ?int              $id,
        public int               $jobId,
        public string            $name,
        public string            $url,
        public string            $urlHost,
        public string            $type,
        public array             $operation,
        public CrawlJobTaskState $state,
    )
    {

    }
}
