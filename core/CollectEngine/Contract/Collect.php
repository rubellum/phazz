<?php

namespace CollectEngine\Contract;

use JobAccess\Contract\DownloadSuspend;
use JobAccess\Contract\DownloadSuspendInput;

readonly class Collect
{
    public function __construct(
        private DownloadSuspend $downloadSuspend,
        private Downloader      $downloader,
        private Extractor       $extractor,
        private Compressor      $compressor,
    )
    {

    }

    public function collect(CollectInput $input): CollectOutput
    {
        $this->downloadSuspend->suspend(
            new DownloadSuspendInput(
                url: $input->url,
            ),
        );

        $downloadOutput = $this->downloader->download(
            new DownloaderInput(
                url: $input->url,
            ),
        );

        $extractOutput = $this->extractor->extract(
            new ExtractorInput(
                resources: $downloadOutput->resources,
                operation: $input->operation,
            ),
        );

        $compressOutput = $this->compressor->compress(
            new CompressorInput(
                resources: $extractOutput->resources,
                operation: $input->operation,
            ),
        );

        return new CollectOutput(
            contentFilePath: $compressOutput->resources->export(),
            resourceFiles: $compressOutput->resources->resourceFiles,
        );
    }
}
