<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetTimeseriesDataRequest
{
    /**
     * Pass metric Id
     *
     *
     *
     * @var GetTimeseriesDataMetricId $metricId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=metricId')]
    public GetTimeseriesDataMetricId $metricId;

    /**
     * This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.
     *
     *
     *
     * @var GetTimeseriesDataTimespan $timespan
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=timespan[]')]
    public GetTimeseriesDataTimespan $timespan;

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
     * Pass this value to group the metrics list by.
     *
     *
     *
     * @var ?GroupBy $groupBy
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=groupBy')]
    public ?GroupBy $groupBy = null;

    /**
     * The order direction to sort the metrics list by.
     *
     *
     *
     * @var ?GetTimeseriesDataSortOrder $sortOrder
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=sortOrder')]
    public ?GetTimeseriesDataSortOrder $sortOrder = null;

    /**
     * The measurement for the given metrics.
     *
     * Possible Values : [95th, median, avg, count or sum]
     *
     *
     * @var ?string $measurement
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=measurement')]
    public ?string $measurement = null;

    /**
     * @param  GetTimeseriesDataMetricId  $metricId
     * @param  GetTimeseriesDataTimespan  $timespan
     * @param  ?GroupBy  $groupBy
     * @param  ?GetTimeseriesDataSortOrder  $sortOrder
     * @param  ?string  $measurement
     * @param  ?string  $filterby
     * @phpstan-pure
     */
    public function __construct(GetTimeseriesDataMetricId $metricId, GetTimeseriesDataTimespan $timespan, ?string $filterby = null, ?GroupBy $groupBy = GroupBy::Minute, ?GetTimeseriesDataSortOrder $sortOrder = GetTimeseriesDataSortOrder::Asc, ?string $measurement = 'avg')
    {
        $this->metricId = $metricId;
        $this->timespan = $timespan;
        $this->filterby = $filterby;
        $this->groupBy = $groupBy;
        $this->sortOrder = $sortOrder;
        $this->measurement = $measurement;
    }
}