<?php

namespace CollectEngine\Contract;

readonly class CollectOutput
{
    public function __construct(
        public string $contentFilePath,
        public array $resourceFiles,
    )
    {
    }
}
