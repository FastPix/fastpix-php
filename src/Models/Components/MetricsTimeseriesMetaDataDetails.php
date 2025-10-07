<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** MetricsTimeseriesMetaDataDetails - Retrieves breakdown values for a specified metric and timespan */
class MetricsTimeseriesMetaDataDetails
{
    /**
     * the unit for aggregating the timeseries data.
     *
     * @var ?string $granularity
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('granularity')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $granularity = null;

    /**
     * defines the field or dimension on which the aggregation is to be   applied.
     *
     * @var ?string $aggregation
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('aggregation')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $aggregation = null;

    /**
     * @param  ?string  $granularity
     * @param  ?string  $aggregation
     * @phpstan-pure
     */
    public function __construct(?string $granularity = null, ?string $aggregation = null)
    {
        $this->granularity = $granularity;
        $this->aggregation = $aggregation;
    }
}