<?php

namespace OperatorPortal\DataModel;

readonly class CompressedResources
{
    /**
     * @param string[] $filePathList
     */
    public function __construct(
        public array $filePathList,
    )
    {

    }

    public function writeZipArchive(string $zipPath): void
    {
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
            foreach ($this->filePathList as $filePath) {
                $zip->addFile($filePath, basename($filePath));
            }
            $zip->close();
        }
    }
}