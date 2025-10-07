<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class ListByTopContentRequest
{
    /**
     * This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.
     *
     *
     *
     * @var ListByTopContentTimespan $timespan
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=timespan[]')]
    public ListByTopContentTimespan $timespan;

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
     * Pass the limit to display only the rows specified by the value.
     *
     *
     *
     * @var ?int $limit
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=limit')]
    public ?int $limit = null;

    /**
     * @param  ListByTopContentTimespan  $timespan
     * @param  ?string  $filterby
     * @param  ?int  $limit
     * @phpstan-pure
     */
    public function __construct(ListByTopContentTimespan $timespan, ?string $filterby = null, ?int $limit = 10)
    {
        $this->timespan = $timespan;
        $this->filterby = $filterby;
        $this->limit = $limit;
    }
}