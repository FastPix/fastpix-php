<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/**
 * Pass this value to group the metrics list by.
 *
 *
 */
enum GroupBy: string
{
    case Minute = 'minute';
    case TenMinutes = 'ten_minutes';
    case Hour = 'hour';
    case Day = 'day';
}
