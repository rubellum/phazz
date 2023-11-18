<?php

namespace CollectEngine\Contract;

use CollectEngine\DataModel\Resources;

readonly class CompressorOutput
{
    public function __construct(
        public Resources $resources,
    )
    {

    }
}
