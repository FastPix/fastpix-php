<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class ListBreakdownValuesRequest
{
    /**
     * Pass metric Id
     *
     *
     *
     * @var ListBreakdownValuesMetricId $metricId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=metricId')]
    public ListBreakdownValuesMetricId $metricId;

    /**
     * This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.
     *
     *
     *
     * @var ListBreakdownValuesTimespan $timespan
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=timespan[]')]
    public ListBreakdownValuesTimespan $timespan;

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
     * Possible Values : ["browser_name", "browser_version", "os_name","os_version" , "device_name", "device_model", "device_type", "device_manufacturer", "player_remote_played",player_name", "player_version", "player_software_name", "player_software_version", "player_resolution", "fp_sdk","fp_sdk_version", "player_autoplay_on", "player_preload_on","video_title",  "video_id", "video_series" ,  "fp_playback_id","fp_live_stream_id", "media_id","video_source_stream_type", "video_source_type", "video_encoding_variant", "experiment_name", "sub_property_id", "drm_type","asn_name", "cdn", "video_source_hostname", "connection_type", "view_session_id","continent","country", "region","viewer_id", "error_code", "exit_before_video_start", "view_has_ad", "video_startup_failed" , "page_context", "playback_failed".]
     *
     *
     * @var ?string $groupBy
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=groupBy')]
    public ?string $groupBy = null;

    /**
     * Pass the limit to display only the rows specified by the value.
     *
     *
     *
     * @var ?int $limit
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=limit')]
    public ?int $limit = null;

    /**
     * Pass the offset value to indicate the page number.
     *
     *
     *
     * @var ?int $offset
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=offset')]
    public ?int $offset = null;

    /**
     * Pass this value to order the metrics list by.
     *
     *
     *
     * @var ?string $orderBy
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=orderBy')]
    public ?string $orderBy = null;

    /**
     * The order direction to sort the metrics list by.
     *
     *
     *
     * @var ?ListBreakdownValuesSortOrder $sortOrder
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=sortOrder')]
    public ?ListBreakdownValuesSortOrder $sortOrder = null;

    /**
     * The measurement for the given metrics.
     *
     *
     *
     * @var ?string $measurement
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=measurement')]
    public ?string $measurement = null;

    /**
     * @param  ListBreakdownValuesMetricId  $metricId
     * @param  ListBreakdownValuesTimespan  $timespan
     * @param  ?string  $filterby
     * @param  ?int  $limit
     * @param  ?int  $offset
     * @param  ?string  $groupBy
     * @param  ?string  $orderBy
     * @param  ?ListBreakdownValuesSortOrder  $sortOrder
     * @param  ?string  $measurement
     * @phpstan-pure
     */
    public function __construct(ListBreakdownValuesMetricId $metricId, ListBreakdownValuesTimespan $timespan, ?string $filterby = null, ?string $groupBy = null, ?int $limit = 10, ?int $offset = 1, ?string $orderBy = 'views', ?ListBreakdownValuesSortOrder $sortOrder = ListBreakdownValuesSortOrder::Asc, ?string $measurement = 'avg')
    {
        $this->metricId = $metricId;
        $this->timespan = $timespan;
        $this->filterby = $filterby;
        $this->groupBy = $groupBy;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
        $this->sortOrder = $sortOrder;
        $this->measurement = $measurement;
    }
}