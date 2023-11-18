<?php

namespace CrawlerManager\Contract;

use ContentAccess\Contract\ContentLoad;
use ContentAccess\Contract\ContentLoadInput;
use JobAccess\Contract\Task\TaskConfirmation;
use JobAccess\Contract\Task\TaskConfirmationInput;

readonly class JobResultRetrieving
{
    public function __construct(
        private TaskConfirmation $taskConfirmation,
        private ContentLoad      $contentLoad,
    )
    {

    }

    public function retrieve(JobResultRetrievingInput $input): JobResultRetrievingOutput
    {
        $task = $this->taskConfirmation->confirm(
            new TaskConfirmationInput(
                $input->taskId,
            )
        )->task;

        $content = $this->contentLoad->load(
            new ContentLoadInput(
                path: $task->path,
            ),
        );

        return new JobResultRetrievingOutput(
            content: $content->content,
            path: $task->path,
        );
    }
}
