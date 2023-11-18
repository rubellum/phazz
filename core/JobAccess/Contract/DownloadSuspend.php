<?php

namespace JobAccess\Contract;

use JobAccess\DataAccess\DownloadLimitAccess;
use JobAccess\DataModel\UrlHost;
use JobAccess\Exception\JobAccessException;

readonly class DownloadSuspend
{
    public function __construct(
        private DownloadLimitAccess $downloadLimitAccess,
    )
    {

    }

    /**
     * @param DownloadSuspendInput $input
     * @return DownloadSuspendOutput
     * @throws JobAccessException if the database operation fails
     */
    public function suspend(DownloadSuspendInput $input): DownloadSuspendOutput
    {
        $downloadLimit = $this->downloadLimitAccess->find(UrlHost::parse($input->url));

        $extendedDownloadLimit = $downloadLimit->extendSuspended();

        $this->downloadLimitAccess->save($extendedDownloadLimit);

        return new DownloadSuspendOutput(
            downloadLimit: $extendedDownloadLimit,
        );
    }
}
