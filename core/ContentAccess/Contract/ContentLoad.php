<?php

namespace ContentAccess\Contract;

use ContentAccess\DataAccess\FileContentStore;
use ContentAccess\Exception\Exception;

readonly class ContentLoad
{
    public function __construct(
        private FileContentStore $fileContentStore,
    )
    {

    }

    /**
     * @param ContentLoadInput $input
     * @return ContentLoadOutput
     * @throws Exception
     */
    public function load(ContentLoadInput $input): ContentLoadOutput
    {
        $content = $this->fileContentStore->load($input->path);

        if ($content === null) {
            throw new Exception('Content not found');
        }

        return new ContentLoadOutput(
            content: $content,
        );
    }
}
