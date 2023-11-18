<?php

namespace ContentAccess\DataModel;

readonly class ContentData
{
    public function __construct(
        public string $contentFilePath,
        public string $body,
    )
    {

    }

    /**
     * @param string $contentFilePath
     * @return self
     */
    public static function read(string $contentFilePath): self
    {
        return new ContentData(
            contentFilePath: $contentFilePath,
            body: file_get_contents($contentFilePath),
        );
    }
}
