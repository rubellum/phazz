<?php

namespace JobAccess\DataModel;

readonly class CrawlSchema
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $type,
        public array  $operations,
    )
    {

    }
}
