<?php

namespace CollectEngine\Contract;

use CollectEngine\DataModel\Resources;

readonly class ExtractorInput
{
    public function __construct(
        public Resources $resources,
        public array $operation,
    )
    {

    }
}
