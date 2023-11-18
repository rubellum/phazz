<?php

namespace JobAccess\Contract\Job;

use JobAccess\DataAccess\CrawlJobAccess;
use JobAccess\Exception\JobAccessException;

readonly class JobConfirmation
{
    public function __construct(
        private CrawlJobAccess $jobAccess,
    )
    {

    }

    /**
     * @param JobConfirmationInput $input
     * @return JobConfirmationOutput
     * @throws JobAccessException
     */
    public function confirm(JobConfirmationInput $input): JobConfirmationOutput
    {
        $job = $this->jobAccess->findById($input->id);

        if ($job === null) {
            throw new JobAccessException('Job not found');
        }

        return new JobConfirmationOutput(
            job: $job,
        );
    }
}
