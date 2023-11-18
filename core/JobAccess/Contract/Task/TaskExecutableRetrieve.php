<?php

namespace JobAccess\Contract\Task;

use JobAccess\DataAccess\CrawlJobTaskAccess;
use JobAccess\DataAccess\TaskExecutableRetrieveQuery;

readonly class TaskExecutableRetrieve
{

    public function __construct(
        private TaskExecutableRetrieveQuery $query,
        private CrawlJobTaskAccess          $taskAccess,
    )
    {

    }

    public function retrieve(TaskExecutableRetrieveInput $input): TaskExecutableRetrieveOutput
    {
        $taskIds = $this->query->retrieve();

        $tasks = $this->taskAccess->find([
            'ids' => $taskIds,
        ]);

        return new TaskExecutableRetrieveOutput(
            tasks: $tasks,
        );
    }
}

