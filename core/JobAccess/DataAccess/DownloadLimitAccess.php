<?php

namespace JobAccess\DataAccess;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use JobAccess\DataModel\DownloadLimit;
use JobAccess\DataModel\UrlHost;
use JobAccess\Exception\JobAccessException;

readonly class DownloadLimitAccess
{
    public function __construct(
        private Connection $conn
    )
    {

    }

    /**
     * @throws JobAccessException if the database operation fails
     */
    public function find(UrlHost $urlHost): ?DownloadLimit
    {
        $sql = <<<SQL
SELECT
    url_host,
    suspended_until
FROM
    download_limits
WHERE
    url_host = :url_host
SQL;
        try {
            $row = $this->conn->fetchAssociative($sql, [
                'url_host' => $urlHost,
            ]);
        } catch (Exception $e) {
            throw new JobAccessException($e->getMessage(), $e->getCode(), $e);
        }
        if (!$row) {
            return new DownloadLimit(
                urlHost: $urlHost,
                suspendedUntil: null,
            );
        }

        return new DownloadLimit(
            urlHost: $urlHost,
            suspendedUntil: \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['suspended_until']),
        );
    }

    /**
     * @param DownloadLimit $downloadLimit
     * @return void
     * @throws JobAccessException if the database operation fails
     */
    public function save(DownloadLimit $downloadLimit): void
    {
        try {
            $id = $this->conn->fetchOne("SELECT id FROM download_limits WHERE url_host = :url_host", [
                'url_host' => $downloadLimit->urlHost,
            ]);

            if ($id) {
                $this->conn->update('download_limits', [
                    'suspended_until' => $downloadLimit->suspendedUntil->format('Y-m-d H:i:s')
                ], [
                    'id' => $id,
                ]);
            } else {
                $this->conn->insert('download_limits', [
                    'url_host' => $downloadLimit->urlHost,
                    'suspended_until' => $downloadLimit->suspendedUntil->format('Y-m-d H:i:s'),
                ]);
            }
        } catch (Exception $e) {
            throw new JobAccessException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
