<?php

namespace OperatorPortal\Aggregation;

use JobAccess\Contract\Task\TaskConfirmation;
use JobAccess\Contract\Task\TaskConfirmationInput;
use JobAccess\DataModel\CrawlJobTaskState;
use OperatorPortal\DataModel\Label\DownloadLimitLabel;
use OperatorPortal\DataModel\StateColorScheme;
use Symfony\Component\Routing\RouterInterface;

readonly class TaskConfirmAggregation
{
    public function __construct(
        private RouterInterface  $router,
        private TaskConfirmation $confirmation,
    )
    {

    }

    /**
     * @param array $params
     * @return array
     */
    public function render(array $params): array
    {
        $result = $this->confirmation->confirm(
            new TaskConfirmationInput(
                id: $params['id'],
            ),
        );

        $task = $result->task;
        $downloadLimit = $result->downloadLimit;

        return [
            'item' => [
                'id' => $task->id,
                'name' => $task->name,
                'url' => $task->url,
                'type' => $task->type,
                'operation' => $task->operation,
                'path' => $task->path,
                'state' => $task->state->getLabel(),
                'downloadLimitLabel' => DownloadLimitLabel::label($downloadLimit),
                'stateColorScheme' => StateColorScheme::convert($task->state),
                'showRunLink' => ( // todo
                    $task->state === CrawlJobTaskState::QUEUED &&
                    $downloadLimit->isAvailable()
                ),
                'showDownloadLink' => $task->state === CrawlJobTaskState::SUCCESS,
                'downloadLink' => $this->router->generate('operator_portal.job.retrieve', ['id' => $task->id]),
                'jobLink' => $this->router->generate('operator_portal.job.confirm', ['id' => $task->jobId]),
            ],
        ];
    }
}
