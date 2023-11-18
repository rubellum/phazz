<?php

namespace JobAccess\DataModel;

enum CrawlJobTaskState: string
{
    case QUEUED = 'queued';
    case DOING = 'doing';
    case SUCCESS = 'success';
    case ERROR = 'error';

    public function getLabel(): string
    {
        return [
            self::QUEUED->value => 'Queued',
            self::DOING->value => 'Running',
            self::SUCCESS->value => 'Success',
            self::ERROR->value => 'Failure',
        ][$this->value] ?? 'Unknown';
    }

    public function getStateHeadline(): string
    {
        return [
            self::QUEUED->value => 'Status',
            self::DOING->value => 'Status',
            self::SUCCESS->value => 'Result',
            self::ERROR->value => 'Result',
        ][$this->value] ?? 'Status';
    }
}
