<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class ListOverallValuesRequest
{
    /**
     * Pass metric Id
     *
     *
     *
     * @var ListOverallValuesMetricId $metricId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=metricId')]
    public ListOverallValuesMetricId $metricId;

    /**
     * This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.
     *
     *
     *
     * @var ListOverallValuesTimespan $timespan
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=timespan[]')]
    public ListOverallValuesTimespan $timespan;

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
     * @param  ListOverallValuesMetricId  $metricId
     * @param  ListOverallValuesTimespan  $timespan
     * @param  ?string  $measurement
     * @param  ?string  $filterby
     * @phpstan-pure
     */
    public function __construct(ListOverallValuesMetricId $metricId, ListOverallValuesTimespan $timespan, ?string $filterby = null, ?string $measurement = 'avg')
    {
        $this->metricId = $metricId;
        $this->timespan = $timespan;
        $this->filterby = $filterby;
        $this->measurement = $measurement;
    }
}