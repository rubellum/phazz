<?php

namespace OperatorPortal\Aggregation;

use JobAccess\Contract\Task\TaskSearch;
use JobAccess\Contract\Task\TaskSearchInput;
use JobAccess\DataModel\CrawlJobTaskState;
use OperatorPortal\DataModel\StateColorScheme;
use Symfony\Component\Routing\RouterInterface;

readonly class TaskSearchAggregation
{
    public function __construct(
        private RouterInterface $router,
        private TaskSearch      $taskSearch,
    )
    {

    }

    /**
     * @param array $params
     * @return array
     */
    public function render(array $params): array
    {
        $searchResult = $this->taskSearch->search(
            new TaskSearchInput(),
        );

        $items = [];
        foreach ($searchResult->items as $item) {
            $items[] = [
                'id' => $item->id,
                'name' => $item->name,
                'url' => $item->url,
                'operation' => $item->operation,
                'state' => $item->state->getLabel(),
                'stateColorScheme' => StateColorScheme::convert($item->state),
                'showDownloadLink' => $item->state === CrawlJobTaskState::SUCCESS,
                'downloadLink' => $this->router->generate('operator_portal.job.retrieve', ['id' => $item->id]),
                'detailLink' => $this->router->generate('operator_portal.task.confirm', ['id' => $item->id]),
            ];
        }

        return [
            'items' => $items,
            'totalCount' => $searchResult->totalCount,
        ];
    }
}
