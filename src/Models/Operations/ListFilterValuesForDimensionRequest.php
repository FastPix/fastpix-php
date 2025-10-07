<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class ListFilterValuesForDimensionRequest
{
    /**
     * Pass Dimensions id
     *
     *
     *
     * @var DimensionsId $dimensionsId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=dimensionsId')]
    public DimensionsId $dimensionsId;

    /**
     * This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.
     *
     *
     *
     * @var ListFilterValuesForDimensionTimespan $timespan
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=timespan[]')]
    public ListFilterValuesForDimensionTimespan $timespan;

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
     * @param  DimensionsId  $dimensionsId
     * @param  ListFilterValuesForDimensionTimespan  $timespan
     * @param  ?string  $filterby
     * @phpstan-pure
     */
    public function __construct(DimensionsId $dimensionsId, ListFilterValuesForDimensionTimespan $timespan, ?string $filterby = null)
    {
        $this->dimensionsId = $dimensionsId;
        $this->timespan = $timespan;
        $this->filterby = $filterby;
    }
}