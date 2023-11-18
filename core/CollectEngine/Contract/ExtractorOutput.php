<?php

namespace CollectEngine\Contract;

use CollectEngine\DataModel\Resources;

readonly class ExtractorOutput
{
    public function __construct(
        public Resources $resources,
    )
    {

    }
}
