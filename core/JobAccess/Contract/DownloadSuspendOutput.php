<?php

namespace JobAccess\Contract;

use JobAccess\DataModel\DownloadLimit;

readonly class DownloadSuspendOutput
{
    public function __construct(
        public DownloadLimit $downloadLimit,
    )
    {

    }
}
