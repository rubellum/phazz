<?php

namespace JobAccess\DataModel;

readonly class CrawlJobTask
{
    public string $path;

    public function __construct(
        public int $id,
        public int $jobId,
        public string $name,
        public string $url,
        public string $urlHost,
        public string $type,
        public array $operation,
        public CrawlJobTaskState $state,
        public ?DownloadLimit $downloadLimit,
    )
    {
        $this->path = $this->jobId . '-' . $this->id . '-' . md5($url);
    }
}
