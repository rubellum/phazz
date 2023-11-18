<?php

namespace Tests\AcceptanceTest;

use CrawlerManager\Contract\JobExecution;
use CrawlerManager\Contract\JobExecutionInput;
use CrawlerManager\Contract\TaskRegistration;
use CrawlerManager\Contract\TaskRegistrationInput;
use JobAccess\Contract\Job\JobSaving;
use JobAccess\Contract\Job\JobSavingInput;
use JobAccess\Contract\Job\JobSearch;
use JobAccess\Contract\Job\JobSearchInput;
use JobAccess\Contract\Task\TaskSearch;
use JobAccess\DataModel\JobSearchCondition;
use JobAccess\DataModel\JobSearchConditionBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SuspendDownloadTest extends KernelTestCase
{
    public function test_SuspendDownload_Error()
    {
        /** @var JobSaving $jobSaving */
        $jobSaving = static::getContainer()->get(JobSaving::class);
        $jobId = $jobSaving->save(
            new JobSavingInput(
                id: null,
                name: 'test',
                url: 'https://example.rubellum.jp/',
                type: 'single',
                operation: ['single' => ["fields" => []]],
            ),
        )->id;

        // 1回目の実行は成功する
        /** @var TaskRegistration $taskRegistration */
        $taskRegistration = static::getContainer()->get(TaskRegistration::class);
        $taskId = $taskRegistration->execute(
            new TaskRegistrationInput(
                jobId: $jobId,
            ),
        )->taskId;

        /** @var JobExecution $jobExecution */
        $jobExecution = static::getContainer()->get(JobExecution::class);

        $result1 = $jobExecution->execute(
            new JobExecutionInput(
                taskId: $taskId,
            ),
        );
        $this->assertSame([$taskId], $result1->successIds);
        $this->assertSame([], $result1->skippedIds);
        $this->assertSame([], $result1->errorIds);

        // 2回目の実行はスキップされる
        /** @var TaskRegistration $taskRegistration */
        $taskRegistration = static::getContainer()->get(TaskRegistration::class);
        $taskId2 = $taskRegistration->execute(
            new TaskRegistrationInput(
                jobId: $jobId,
            ),
        )->taskId;

        $result2 = $jobExecution->execute(
            new JobExecutionInput(
                taskId: $taskId2,
            ),
        );
        $this->assertSame([], $result2->successIds);
        $this->assertSame([], $result2->errorIds);
        $this->assertSame([$taskId2], $result2->skippedIds);
    }
}
