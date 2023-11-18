<?php

namespace CollectEngine\Contract;

use CollectEngine\DataAccess\HttpFileDownloader;
use CollectEngine\DataModel\Resources;

readonly class Downloader
{
    public function __construct(
        private HttpFileDownloader $fileDownloader,
    )
    {

    }

    public function download(DownloaderInput $input): DownloaderOutput
    {
        $downloadFileName = $this->fileDownloader->download($input->url);

        return new DownloaderOutput(
            resources: (new Resources())->import(
                key: 'download',
                filePath: $downloadFileName,
            )
        );
    }
}
