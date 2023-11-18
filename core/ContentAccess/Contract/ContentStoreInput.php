<?php

namespace ContentAccess\Contract;

readonly class ContentStoreInput
{
    public function __construct(
        public string $path,
        public string $contentFilePath,
    )
    {

    }
}
