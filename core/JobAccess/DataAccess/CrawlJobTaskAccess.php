<?php

namespace JobAccess\DataAccess;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use JobAccess\DataModel\CrawlJobTask;
use JobAccess\DataModel\CrawlJobTaskDraft;
use JobAccess\DataModel\CrawlJobTaskState;
use JobAccess\DataModel\DownloadLimit;
use JobAccess\DataModel\UrlHost;
use JobAccess\Exception\JobAccessException;

readonly class CrawlJobTaskAccess
{
    public function __construct(
        private Connection $conn
    )
    {

    }

    /**
     * @param array $condition
     * @return array{items: CrawlJobTask[], totalCount: int}
     * @throws JobAccessException
     */
    public function search(array $condition): array
    {
        try {
            return [
                'items' => $this->find($condition),
                'totalCount' => $this->count($condition),
            ];
        } catch (\Exception $e) {
            throw new JobAccessException('search error. ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $condition
     * @return array
     * @throws JobAccessException
     */
    public function find(array $condition): array
    {
        try {
            $rows = $this->dispatch(
                $this->conn->createQueryBuilder()
                    ->select(
                        't.id',
                        't.job_id',
                        't.name',
                        't.url',
                        't.url_host',
                        't.type',
                        't.operation',
                        't.state',
                        'd.suspended_until',
                    )
                    ->from('crawl_job_tasks', 't')
                    ->leftJoin('t', 'download_limits', 'd', 't.url_host = d.url_host'),
                $condition,
            )->fetchAllAssociative();

            $items = [];
            foreach ($rows as $row) {
                $items[] = new CrawlJobTask(
                    id: (int)$row['id'],
                    jobId: (int)$row['job_id'],
                    name: $row['name'],
                    url: $row['url'],
                    urlHost: $row['url_host'],
                    type: $row['type'],
                    operation: json_decode($row['operation'], true),
                    state: CrawlJobTaskState::from($row['state']),
                    downloadLimit: new DownloadLimit(
                        urlHost: UrlHost::parse($row['url']),
                        suspendedUntil: $row['suspended_until'] ? new \DateTimeImmutable($row['suspended_until']) : null,
                    ),
                );
            }

            return $items;
        } catch (\Exception $e) {
            throw new JobAccessException('find error:' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $condition
     * @return int
     * @throws JobAccessException
     */
    private function count(array $condition): int
    {
        try {
            return (int)$this->dispatch(
                $this->conn->createQueryBuilder()
                    ->select('COUNT(*) AS count')
                    ->from('crawl_job_tasks', 't'),
                $condition,
            )->fetchOne();
        } catch (\Exception $e) {
            throw new JobAccessException('count error', $e->getCode(), $e);
        }
    }

    private function dispatch(QueryBuilder $builder, array $condition): QueryBuilder
    {
        if (isset($condition['id'])) {
            $builder->andWhere('t.id = :id');
            $builder->setParameter('id', $condition['id']);
        }

        if (isset($condition['jobId'])) {
            $builder->andWhere('t.job_id = :jobId');
            $builder->setParameter('jobId', $condition['jobId']);
        }

        if (isset($condition['state'])) {
            $builder->andWhere('t.state = :state');
            $builder->setParameter('state', $condition['state']);
        }

        if (isset($condition['limit'])) {
            $builder->setMaxResults($condition['limit']);
        }

        if (isset($condition['offset'])) {
            $builder->setFirstResult($condition['offset']);
        }

        if (isset($condition['orderDesc'])) {
            $builder->orderBy('t.' . $condition['orderDesc'], 'DESC');
        }

        if (isset($condition['orderAsc'])) {
            $builder->orderBy('t.' . $condition['orderAsc'], 'ASC');
        }

        return $builder;
    }

    /**
     * @param int $taskId
     * @return CrawlJobTask|null
     * @throws JobAccessException if an error occurs
     */
    public function findOne(int $taskId): ?CrawlJobTask
    {
        $tasks = $this->find([
            'id' => $taskId,
            'limit' => 1,
        ]);
        if (!$tasks) {
            return null;
        }
        return $tasks[0];
    }

    /**
     * @param CrawlJobTaskDraft $task
     * @return array
     * @throws JobAccessException
     */
    public function save(CrawlJobTaskDraft $task): array
    {
        try {
            if ($task->id) {
                $this->conn->update('crawl_job_tasks', [
                    'job_id' => $task->jobId,
                    'name' => $task->name,
                    'url' => $task->url,
                    'url_host' => $task->urlHost,
                    'type' => $task->type,
                    'operation' => json_encode($task->operation, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'state' => $task->state->value,
                ], [
                    'id' => $task->id,
                ]);
                $id = $task->id;
            } else {
                $this->conn->insert('crawl_job_tasks', [
                    'job_id' => $task->jobId,
                    'name' => $task->name,
                    'url' => $task->url,
                    'url_host' => $task->urlHost,
                    'type' => $task->type,
                    'operation' => json_encode($task->operation, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'state' => $task->state->value,
                ]);
                $id = $this->conn->lastInsertId();
            }
        } catch (\Exception $e) {
            throw new JobAccessException('save error', $e->getCode(), $e);
        }
        return [
            'id' => $id,
        ];
    }

    /**
     * @param int $jobTaskId
     * @param CrawlJobTaskState $state
     * @throws JobAccessException
     */
    public function changeState(int $jobTaskId, CrawlJobTaskState $state): void
    {
        try {
            $this->conn->update('crawl_job_tasks', [
                'state' => $state->value,
            ], [
                'id' => $jobTaskId,
            ]);
        } catch (\Exception $e) {
            throw new JobAccessException('change state error', $e->getCode(), $e);
        }
    }
}
