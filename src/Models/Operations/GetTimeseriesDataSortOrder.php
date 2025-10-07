<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/**
 * The order direction to sort the metrics list by.
 *
 *
 */
enum GetTimeseriesDataSortOrder: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}
