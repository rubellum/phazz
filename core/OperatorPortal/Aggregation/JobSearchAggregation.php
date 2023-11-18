<?php

namespace OperatorPortal\Aggregation;

use JobAccess\Contract\Job\JobSearch;
use JobAccess\Contract\Job\JobSearchInput;
use JobAccess\DataModel\JobSearchConditionBuilder;
use Symfony\Component\Routing\RouterInterface;

readonly class JobSearchAggregation
{
    public function __construct(
        private JobSearch       $jobSearch,
        private RouterInterface $router,
    )
    {

    }

    /**
     * @param array $params
     * @return array
     */
    public function render(array $params): array
    {
        $searchResult = $this->jobSearch->search(
            new JobSearchInput(
                condition: (new JobSearchConditionBuilder())
                    ->setNameLike($params['nameLike'] ?? null)
                    ->setUrlLike($params['urlLike'] ?? null)
                    ->build(),
            ),
        );

        $items = [];
        foreach ($searchResult->items as $job) {
            $items[] = [
                'id' => $job->id,
                'name' => $job->name,
                'url' => $job->url,
                'edit_link' => $this->router->generate('operator_portal.job.edit', [
                    'id' => $job->id,
                ]),
                'detail_link' => $this->router->generate('operator_portal.job.confirm', [
                    'id' => $job->id,
                ]),
                'execution_link' => $this->router->generate('operator_portal.job.execution.form', [
                    'id' => $job->id,
                ]),
            ];
        }

        return [
            'items' => $items,
            'totalCount' => $searchResult->totalCount,
        ];
    }
}
