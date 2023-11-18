<?php

namespace JobAccess\DataModel;

use JobAccess\Exception\Exception;
use JobAccess\Exception\JobAccessException;

class CrawlJob
{
    // TODO ここではないどこかに、type別にクラスがあるべきだがまだ見つけられていない
    private static array $taskFirstType = [
        'single' => 'single',
        'list-detail' => 'list',
    ];

    public function __construct(
        public int    $id,
        public string $name,
        public string $url,
        public string $urlHost,
        public string $type,
        public array  $operation,
    )
    {

    }

    /**
     * @return CrawlJobTaskDraft
     * @throws Exception
     */
    public function registerFirstTask(): CrawlJobTaskDraft
    {
        return $this->registerTask($this->type);
    }

    /**
     * @param string $type
     * @return CrawlJobTaskDraft
     * @throws JobAccessException
     */
    public function registerTask(string $type): CrawlJobTaskDraft
    {
        $operation = $this->operation[self::$taskFirstType[$type]] ?? null;

        if ($operation === null) {
            throw new JobAccessException("Invalid task type: $type");
        }

        return new CrawlJobTaskDraft(
            id: null,
            jobId: $this->id,
            name: $this->name,
            url: $this->url,
            urlHost: $this->urlHost,
            type: $this->type,
            operation: $operation,
            state: CrawlJobTaskState::QUEUED,
        );
    }
}
