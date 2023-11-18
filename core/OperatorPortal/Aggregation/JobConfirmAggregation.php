<?php

namespace OperatorPortal\Aggregation;

use JobAccess\Contract\Job\JobConfirmation;
use JobAccess\Contract\Job\JobConfirmationInput;
use Symfony\Component\Routing\RouterInterface;

readonly class JobConfirmAggregation
{
    public function __construct(
        private RouterInterface  $router,
        private JobConfirmation $confirmation,
    )
    {

    }

    /**
     * @param array $params
     * @return array
     */
    public function render(array $params): array
    {
        $job = $this->confirmation->confirm(
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
                'executeLink' => $this->router->generate('operator_portal.job.execution.form', ['id' => $job->id]),
                'editLink' => $this->router->generate('operator_portal.job.edit', ['id' => $job->id]),
            ],
        ];
    }
}
