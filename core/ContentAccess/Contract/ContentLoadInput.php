<?php

namespace ContentAccess\Contract;

readonly class ContentLoadInput
{
    public function __construct(
        public string $path,
    )
    {

    }
}
