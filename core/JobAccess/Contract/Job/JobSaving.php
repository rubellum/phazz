<?php

namespace JobAccess\Contract\Job;

use JobAccess\DataAccess\CrawlJobAccess;
use JobAccess\DataModel\CrawlJobDraft;
use JobAccess\Exception\Exception;

readonly class JobSaving
{

    public function __construct(
        private CrawlJobAccess $jobAccess,
    )
    {
    }

    /**
     * @param JobSavingInput $input
     * @return JobSavingOutput
     * @throws Exception
     */
    public function save(JobSavingInput $input): JobSavingOutput
    {
        $result = $this->jobAccess->save(new CrawlJobDraft(
            id: $input->id,
            name: $input->name,
            url: $input->url,
            type: $input->type,
            operation: $input->operation,
        ));
        return new JobSavingOutput(
            id: $result['id'],
        );
    }
}
