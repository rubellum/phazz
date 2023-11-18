<?php

namespace CollectEngine\Contract;

use CollectEngine\DataModel\Resources;

readonly class CompressorInput
{
    public function __construct(
        public Resources $resources,
        public array $operation,
    )
    {

    }
}
