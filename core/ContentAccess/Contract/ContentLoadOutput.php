<?php

namespace ContentAccess\Contract;

use ContentAccess\DataModel\Content;

readonly class ContentLoadOutput
{
    public function __construct(
        public Content $content,
    )
    {

    }
}
