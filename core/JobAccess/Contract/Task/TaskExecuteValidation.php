<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataAccess\DownloadLimitAccess;
use JobAccess\DataModel\UrlHost;

readonly class TaskExecuteValidation
{
    public function __construct(
        private DownloadLimitAccess $downloadLimitAccess,
    )
    {

    }

    public function validate(TaskExecuteValidationInput $input): TaskExecuteValidationOutput
    {
        $downloadLimit = $this->downloadLimitAccess->find(UrlHost::parse($input->task->url));

        $isSuspended = $downloadLimit->isSuspended(new \DateTimeImmutable());

        $isSkipped = $isSuspended === true;

        return new TaskExecuteValidationOutput(
            isValid: true,
            isSkipped: $isSkipped,
        );
    }
}
