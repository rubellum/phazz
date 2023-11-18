<?php

namespace CollectEngine\Contract;

readonly class Compressor
{
    public function __construct(

    )
    {
    }

    public function compress(CompressorInput $input): CompressorOutput
    {
        $source = $input->resources->export();

        // todo: compress data from $source

        return new CompressorOutput(
            resources: $input->resources->import(
                key: 'compress',
                filePath: $source,
            ),
        );
    }
}
