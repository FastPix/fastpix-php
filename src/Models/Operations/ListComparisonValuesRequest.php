<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class ListComparisonValuesRequest
{
    /**
     * This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.
     *
     *
     *
     * @var ListComparisonValuesTimespan $timespan
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=timespan[]')]
    public ListComparisonValuesTimespan $timespan;

    /**
     * Pass the dimensions and their corresponding values you want to filter the views by. For excluding the values in the filter we can pass '!' before the filter value. The list of filters can be obtained from list of dimensions endpoint.
     *
     * Example Values : [ browser_name:Chrome , os_name:macOS , device_name:Galaxy ]
     *
     *
     * @var ?string $filterby
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=filterby[]')]
    public ?string $filterby = null;

    /**
     * The dimension id in which the views are watched.
     *
     *
     *
     * @var ?ListComparisonValuesDimension $dimension
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=dimension')]
    public ?ListComparisonValuesDimension $dimension = null;

    /**
     * The value for the selected dimension. 
     *
     * For example:
     *  If `dimension` is `browser_name`, the value could be  `Chrome` `,` `Firefox` `etc` .
     *  If `dimension` is `os_name`, the value could be `macOS` `,` `Windows` `etc` .
     *
     *
     * @var ?string $value
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=value')]
    public ?string $value = null;

    /**
     * @param  ListComparisonValuesTimespan  $timespan
     * @param  ?string  $filterby
     * @param  ?ListComparisonValuesDimension  $dimension
     * @param  ?string  $value
     * @phpstan-pure
     */
    public function __construct(ListComparisonValuesTimespan $timespan, ?string $filterby = null, ?ListComparisonValuesDimension $dimension = null, ?string $value = null)
    {
        $this->timespan = $timespan;
        $this->filterby = $filterby;
        $this->dimension = $dimension;
        $this->value = $value;
    }
}