<?php

namespace ContentAccess\DataModel;

readonly class Content
{
    public function __construct(
        public string      $path,
        public ContentData $data,
    )
    {

    }
}
