<?php

namespace CrawlerManager\Contract;

use ContentAccess\DataModel\Content;

readonly class JobResultRetrievingOutput
{
    public function __construct(
        public Content $content,
        public string $path,
    )
    {

    }
}
