<?php

namespace CollectEngine\Contract;

readonly class DownloaderInput
{
    public function __construct(
        public string $url,
    )
    {

    }
}
