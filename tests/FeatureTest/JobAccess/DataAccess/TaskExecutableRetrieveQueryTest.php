<?php

namespace Tests\FeatureTest\JobAccess\DataAccess;

use JobAccess\DataAccess\TaskExecutableRetrieveQuery;
use JobAccess\DataModel\CrawlJobTaskState;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskExecutableRetrieveQueryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function test_RetrieveTaskExecutable_OK(): void
    {
        $data = [
            'crawl_job_tasks' => [
                [
                    'id' => 100,
                    'job_id' => 100,
                    'name' => '100job',
                    'url' => 'https://example100.rubellum.jp/',
                    'url_host' => 'example100.rubellum.jp',
                    'type' => 'single',
                    'operation' => json_encode(['operation' => 'test']),
                    'state' => CrawlJobTaskState::QUEUED->value,
                ],
                [
                    'id' => 101,
                    'job_id' => 100,
                    'name' => '101job',
                    'url' => 'https://example101.rubellum.jp/',
                    'url_host' => 'example101.rubellum.jp',
                    'type' => 'single',
                    'operation' => json_encode(['operation' => 'test']),
                    'state' => CrawlJobTaskState::QUEUED->value,
                ],
                [
                    'id' => 102,
                    'job_id' => 100,
                    'name' => '102job',
                    'url' => 'https://example102.rubellum.jp/',
                    'url_host' => 'example102.rubellum.jp',
                    'type' => 'single',
                    'operation' => json_encode(['operation' => 'test']),
                    'state' => CrawlJobTaskState::QUEUED->value,
                ],
                [
                    'id' => 103,
                    'job_id' => 100,
                    'name' => '103job',
                    'url' => 'https://example103.rubellum.jp/',
                    'url_host' => 'example103.rubellum.jp',
                    'type' => 'single',
                    'operation' => json_encode(['operation' => 'test']),
                    'state' => CrawlJobTaskState::QUEUED->value,
                ]
            ],
            'download_limits' => [
                [ // ダウンロードログが制限日時内なら対象外
                    'url_host' => 'example101.rubellum.jp',
                    'suspended_until' => '2023-01-15 12:00:01',
                ],
                [ // ダウンロードログが制限日時と同日時なら対象外
                    'url_host' => 'example102.rubellum.jp',
                    'suspended_until' => '2023-01-15 12:00:00',
                ],
                [ // ダウンロードログが制限日時を超えていたら対象
                    'url_host' => 'example103.rubellum.jp',
                    'suspended_until' => '2023-01-15 11:59:59',
                ]
            ],
        ];

        $conn = self::getContainer()->get('doctrine.dbal.default_connection');
        foreach ($data as $table => $rows) {
            foreach ($rows as $row) {
                $conn->insert($table, $row);
            }
        }

        $obj = new TaskExecutableRetrieveQuery(
            $this->getContainer()->get('doctrine.dbal.default_connection')
        );
        $rows = $obj->retrieve([
            'now' => '2023-01-15 12:00:00',
        ]);

        $this->assertSame([
            100,
            103,
        ], $rows);
    }
}