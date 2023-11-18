<?php

namespace JobAccess\DataAccess;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use JobAccess\Contract\Job\JobSearchOutput;
use JobAccess\DataModel\CrawlJob;
use JobAccess\DataModel\CrawlJobDraft;
use JobAccess\DataModel\JobSearchCondition;
use JobAccess\DataModel\JobSearchConditionBuilder;
use JobAccess\Exception\JobAccessException;

readonly class CrawlJobAccess
{
    public function __construct(
        private Connection $conn
    )
    {

    }

    /**
     * @param int $id
     * @return CrawlJob|null
     * @throws JobAccessException if an error occurs
     */
    public function findById(int $id): ?CrawlJob
    {
        $rows = $this->find(
            (new JobSearchConditionBuilder())
                ->setId($id)
                ->build(),
        );

        if ($rows) {
            return $rows[0];
        }

        return null;
    }

    /**
     * @param JobSearchCondition $condition
     * @return JobSearchOutput
     * @throws JobAccessException if an error occurs
     */
    public function search(JobSearchCondition $condition): JobSearchOutput
    {
        return new JobSearchOutput(
            items: $this->find($condition),
            totalCount: $this->count($condition),
        );
    }

    /**
     * @param JobSearchCondition $condition
     * @return CrawlJob[]
     * @throws JobAccessException if an error occurs
     */
    public function find(JobSearchCondition $condition): array
    {
        try {
            $query = $this->conn->createQueryBuilder()->select('j.id', 'j.name', 'j.url', 'j.url_host', 'j.type', 'j.operation');
            $rows = $this->dispatch($query, $condition)->fetchAllAssociative();

            $items = [];
            foreach ($rows as $row) {
                $items[] = new CrawlJob(
                    id: (int)$row['id'],
                    name: $row['name'],
                    url: $row['url'],
                    urlHost: $row['url_host'],
                    type: $row['type'],
                    operation: $row['operation'] ? json_decode($row['operation'], true, 512, JSON_THROW_ON_ERROR) : [],
                );
            }

            return $items;
        } catch (\Exception $e) {
            throw new JobAccessException('fetch error', $e->getCode(), $e);
        }
    }

    /**
     * @param JobSearchCondition $condition
     * @return int
     * @throws JobAccessException if an error occurs
     */
    public function count(JobSearchCondition $condition): int
    {
        try {
            $query = $this->conn->createQueryBuilder()->select('COUNT(*) AS count');

            return (int)$this->dispatch($query, $condition)->fetchOne();
        } catch (\Exception $e) {
            throw new JobAccessException('count error', $e->getCode(), $e);
        }
    }

    private function dispatch(QueryBuilder $query, JobSearchCondition $condition): QueryBuilder
    {
        $query->from('crawl_jobs', 'j');

        if ($condition->ids !== null) {
            $query->andWhere('j.id IN (:ids)')
                ->setParameter('ids', $condition->ids);
        }

        if ($condition->id !== null) {
            $query->andWhere('j.id = :id')
                ->setParameter('id', $condition->id);
        }

        if ($condition->name !== null) {
            $query->andWhere('j.name = :name')
                ->setParameter('name', $condition->name);
        }

        if ($condition->nameLike !== null) {
            $query->andWhere('j.name LIKE :name_like')
                ->setParameter('name_like', '%' . $condition->nameLike . '%');
        }

        if ($condition->url !== null) {
            $query->andWhere('j.url = :url')
                ->setParameter('url', $condition->url);
        }

        if ($condition->urlLike !== null) {
            $query->andWhere('j.url LIKE :url_like')
                ->setParameter('url_like', '%' . $condition->urlLike . '%');
        }

        if ($condition->type !== null) {
            $query->andWhere('j.type = :type')
                ->setParameter('type', $condition->type);
        }

        if ($condition->limit !== null) {
            $query->setMaxResults($condition->limit);
        }

        if ($condition->offset !== null) {
            $query->setFirstResult($condition->offset);
        }

        if ($condition->orderAsc !== null) {
            $query->orderBy($condition->orderAsc, 'ASC');
        } elseif ($condition->orderDesc !== null) {
            $query->orderBy($condition->orderDesc, 'DESC');
        }

        return $query;
    }

    /**
     * @param CrawlJobDraft $job
     * @return array{id: int}
     * @throws JobAccessException if an error occurs
     */
    public function save(CrawlJobDraft $job): array
    {
        try {
            if ($job->id) {
                $this->conn->update('crawl_jobs', [
                    'name' => $job->name,
                    'url' => $job->url,
                    'url_host' => $job->urlHost,
                    'type' => $job->type,
                    'operation' => json_encode($job->operation, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                ], [
                    'id' => $job->id,
                ]);
                $jobId = $job->id;
            } else {
                $this->conn->insert('crawl_jobs', [
                    'name' => $job->name,
                    'url' => $job->url,
                    'url_host' => $job->urlHost,
                    'type' => $job->type,
                    'operation' => json_encode($job->operation, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                ]);
                $jobId = $this->conn->lastInsertId();
            }
            return [
                'id' => $jobId,
            ];
        } catch (\Exception $e) {
            throw new JobAccessException($e->getMessage(), $e->getCode(), $e);
        }
    }
}