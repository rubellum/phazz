<?php

namespace CollectEngine\Contract;

readonly class CollectInput
{
    public function __construct(
        public string $url,
        public array $operation,
    )
    {
    }
}
