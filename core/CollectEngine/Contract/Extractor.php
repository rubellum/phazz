<?php

namespace CollectEngine\Contract;

use CollectEngine\Operation\ExtractOperationResolver;

readonly class Extractor
{
    public function __construct(
        private ExtractOperationResolver $resolver,
    )
    {
    }

    public function extract(ExtractorInput $input): ExtractorOutput
    {
        // todo: extract data from $source
        $operation = $this->resolver->resolve(
            $input->operation['extractor'] ?? 'html2json',
        );
        return $operation->extract($input);
    }
}
