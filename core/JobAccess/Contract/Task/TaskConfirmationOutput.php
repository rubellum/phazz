<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataModel\CrawlJobTask;
use JobAccess\DataModel\DownloadLimit;

readonly class TaskConfirmationOutput
{
    public function __construct(
        public CrawlJobTask $task,
        public DownloadLimit $downloadLimit,
    )
    {

    }
}
