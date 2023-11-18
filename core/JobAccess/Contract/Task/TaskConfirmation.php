<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataAccess\CrawlJobTaskAccess;
use JobAccess\DataAccess\DownloadLimitAccess;
use JobAccess\DataModel\UrlHost;
use JobAccess\Exception\JobAccessException;

readonly class TaskConfirmation
{
    public function __construct(
        private CrawlJobTaskAccess  $taskAccess,
        private DownloadLimitAccess $downloadSuspendedAccess,
    )
    {

    }

    /**
     * @param TaskConfirmationInput $input
     * @return TaskConfirmationOutput
     * @throws JobAccessException when task not found
     */
    public function confirm(TaskConfirmationInput $input): TaskConfirmationOutput
    {
        $task = $this->taskAccess->findOne($input->id);

        if ($task === null) {
            throw new JobAccessException('Task not found');
        }

        $downloadLimit = $this->downloadSuspendedAccess->find(UrlHost::parse($task->url));

        return new TaskConfirmationOutput(
            task: $task,
            downloadLimit: $downloadLimit,
        );
    }
}
