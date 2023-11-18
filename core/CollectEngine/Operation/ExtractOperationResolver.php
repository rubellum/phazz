<?php

namespace CollectEngine\Operation;

use CollectEngine\Exception\CollectEngineException;

readonly class ExtractOperationResolver
{
    /**
     * @param ExtractOperation[] $extractors
     */
    public function __construct(
        private array $extractors
    )
    {

    }

    /**
     * @param string $type
     * @return ExtractOperation
     * @throws CollectEngineException
     */
    public function resolve(string $type): ExtractOperation
    {
        if (!isset($this->extractors[$type])) {
            throw new CollectEngineException('ExtractOperation not found');
        }

        return $this->extractors[$type];
    }
}
