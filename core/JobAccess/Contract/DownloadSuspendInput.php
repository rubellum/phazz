<?php

namespace JobAccess\Contract;

readonly class DownloadSuspendInput
{
    public function __construct(
        public string $url,
    )
    {

    }
}
