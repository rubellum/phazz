<?php

namespace ContentAccess\Contract;

use ContentAccess\DataAccess\FileContentStore;
use ContentAccess\DataModel\Content;
use ContentAccess\DataModel\ContentData;

readonly class ContentStore
{
    public function __construct(
        private FileContentStore $fileContentStore,
    )
    {

    }

    public function store(ContentStoreInput $input): ContentStoreOutput
    {
        // todo ここで本来はS3などの外部ストレージに保存する
        $storePath = $this->fileContentStore->store(
            new Content(
                path: $input->path,
                data: ContentData::read($input->contentFilePath),
            )
        );

        return new ContentStoreOutput(
            path: $storePath,
        );
    }
}
