<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/**
 * This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.
 *
 *
 */
enum ListFilterValuesForDimensionTimespan: string
{
    case Sixtyminutes = '60:minutes';
    case Sixhours = '6:hours';
    case TwentyFourhours = '24:hours';
    case Threedays = '3:days';
    case Sevendays = '7:days';
    case Thirtydays = '30:days';
}
