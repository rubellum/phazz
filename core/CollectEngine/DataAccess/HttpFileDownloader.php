<?php

namespace CollectEngine\DataAccess;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

readonly class HttpFileDownloader
{
    public function __construct(
        private LoggerInterface $logger,
    )
    {

    }

    /**
     * Downloads the content of a given URL and saves it to a temporary file.
     *
     * @param string $url The URL of the resource to download.
     * @return string The path of the temporary file where the content is saved.
     */
    public function download(string $url): string
    {
        $this->logger->info('Downloading:' . $url);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'PhazzBot/1.0');
        $response = curl_exec($curl);
        curl_close($curl);

        $fileSystem = new Filesystem();
        $tempnam = $fileSystem->tempnam(sys_get_temp_dir(), '');
        $fileSystem->dumpFile($tempnam, $response);

        return $tempnam;
    }
}
