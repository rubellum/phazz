<?php

namespace CollectEngine\Contract;

use CollectEngine\DataModel\Resources;

readonly class DownloaderOutput
{
    public function __construct(
        public Resources $resources,
    )
    {

    }
}
