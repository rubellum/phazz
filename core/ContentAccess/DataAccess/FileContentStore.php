<?php

namespace ContentAccess\DataAccess;

use ContentAccess\DataModel\Content;
use ContentAccess\DataModel\ContentData;
use JobAccess\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;

readonly class FileContentStore
{
    public function __construct(
        private ContentMetadataStore $contentMetadataStore,
        public string                $saveDir,
    )
    {

    }

    public function load(string $path): ?Content
    {
        $savePath = $this->saveDir . DIRECTORY_SEPARATOR . $path;

        return new Content(
            path: $path,
            data: ContentData::read($savePath),
        );
    }

    /**
     * @throws Exception failed to save content
     */
    public function store(Content $content): string
    {
        $savePath = $this->saveDir . DIRECTORY_SEPARATOR . $content->path;

        // Plan to support various formats in the future
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($savePath, fopen($content->data->contentFilePath, 'rb'));

        $this->contentMetadataStore->save($content);

        return $savePath;
    }
}
