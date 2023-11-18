<?php

namespace JobAccess\DataAccess;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

readonly class TaskExecutableRetrieveQuery
{
    public function __construct(
        private Connection $conn
    )
    {

    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function retrieve(array $params = []): array
    {
        $now = $params['now'] ?? date('Y-m-d H:i:s');

        $sql = <<<SQL
SELECT
    t.id
FROM
    crawl_job_tasks AS t
LEFT JOIN
    download_limits AS d
    ON t.url_host = d.url_host
WHERE
    (d.url_host IS NULL) OR
    (d.url_host IS NOT NULL AND d.suspended_until < :now)
SQL;
        $rows = $this->conn->fetchAllAssociative($sql, [
            'now' => $now,
        ]);

        $items = [];
        foreach ($rows as $row) {
            $items[] = (int)$row['id'];
        }

        return $items;
    }
}
