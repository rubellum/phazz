<?php

namespace OperatorPortal\Aggregation;

use JobAccess\Contract\Job\JobConfirmation;
use JobAccess\Contract\Job\JobConfirmationInput;

readonly class JobExecutionAggregation
{
    public function __construct(
        private JobConfirmation $jobConfirmation,
    )
    {

    }

    /**
     * @param array $params
     * @return array
     */
    public function render(array $params): array
    {
        $job = $this->jobConfirmation->confirm(
            new JobConfirmationInput(
                id: $params['id'],
            ),
        )->job;

        return [
            'item' => [
                'id' => $job->id,
                'name' => $job->name,
                'url' => $job->url,
                'type' => $job->type,
            ],
        ];
    }
}
