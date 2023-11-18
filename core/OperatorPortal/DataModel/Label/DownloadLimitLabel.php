<?php

namespace OperatorPortal\DataModel\Label;

use JobAccess\DataModel\DownloadLimit;

enum DownloadLimitLabel: string
{
    case AVAILABLE = 'Available';
    case SUSPENDED = 'Suspended';
    case UNAVAILABLE = 'Unavailable';

    public static function label(DownloadLimit $downloadLimit): string
    {
        if ($downloadLimit->isSuspended()) {
            return self::SUSPENDED->value;
        } elseif ($downloadLimit->isUnavailable()) {
            return self::UNAVAILABLE->value;
        }
        return self::AVAILABLE->value;
    }
}