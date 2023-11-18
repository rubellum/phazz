<?php

namespace JobAccess\DataModel;

readonly class CrawlSchemaDraft
{
    public function __construct(
        public ?int   $id,
        public string $name,
        public string $type,
        public array  $operations,
    )
    {

    }
}
