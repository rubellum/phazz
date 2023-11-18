<?php

namespace ContentAccess\Contract;

readonly class ContentStoreOutput
{
    public function __construct(
        public string $path,
    )
    {
    }
}
