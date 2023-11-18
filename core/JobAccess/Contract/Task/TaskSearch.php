<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataAccess\CrawlJobTaskAccess;
use JobAccess\Exception\Exception;

readonly class TaskSearch
{

    public function __construct(
        private CrawlJobTaskAccess $taskAccess,
    )
    {

    }

    /**
     * @param TaskSearchInput $input
     * @return TaskSearchOutput
     * @throws Exception
     */
    public function search(TaskSearchInput $input): TaskSearchOutput
    {
        $result = $this->taskAccess->search([
            'jobId' => $input->jobId,
        ]);

        return new TaskSearchOutput(
            items: $result['items'],
            totalCount: $result['totalCount'],
        );
    }
}
