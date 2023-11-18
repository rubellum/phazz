<?php

namespace OperatorPortal\DataModel;

use JobAccess\DataModel\CrawlJobTaskState;

class StateColorScheme
{
    public static function convert(CrawlJobTaskState $state): string
    {
        return match ($state) {
            CrawlJobTaskState::QUEUED => '',
            CrawlJobTaskState::DOING => 'purple',
            CrawlJobTaskState::SUCCESS => 'green',
            CrawlJobTaskState::ERROR => 'red',
        };
    }
}