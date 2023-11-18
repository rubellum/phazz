<?php

namespace ContentAccess\DataAccess;

use ContentAccess\DataModel\Content;
use Doctrine\DBAL\Connection;
use JobAccess\Exception\Exception;

readonly class ContentMetadataStore
{
    public function __construct(
        private Connection $conn,
    )
    {

    }

    /**
     * @param Content $content
     * @return void
     * @throws Exception Failed to save content metadata.
     */
    public function save(Content $content): void
    {
        try {
            $id = $this->conn->fetchOne(
                'SELECT id FROM content_metadata WHERE path = :path LIMIT 1',
                [
                    'path' => $content->path,
                ]
            );
            if (!$id) {
                $this->conn->insert(
                    'content_metadata',
                    [
                        'path' => $content->path,
                    ],
                );
            }
        } catch (\Exception $e) {
            throw new Exception('Failed to save content metadata.', 0, $e);
        }
    }
}
