<?php

namespace JobAccess\DataModel;

use JobAccess\Exception\JobAccessException;

class CrawlJobDraft
{
    public string $urlHost;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $url
     * @param string $type
     * @param array $operation
     * @throws JobAccessException
     */
    public function __construct(
        public ?int   $id,
        public string $name,
        public string $url,
        public string $type,
        public array  $operation,
    )
    {
        $this->urlHost = UrlHost::parse($url)->urlHost;
    }
}
