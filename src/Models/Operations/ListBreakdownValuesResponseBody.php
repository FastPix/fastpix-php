<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** ListBreakdownValuesResponseBody - Get filter/ dimension value details by dimension name. */
class ListBreakdownValuesResponseBody
{
    /**
     * It demonstrates whether the request is successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * Retrieves breakdown values for a specified metric and timespan
     *
     * @var ?Components\MetricsTimeseriesMetaDataDetails $metaData
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metaData')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MetricsTimeseriesMetaDataDetails|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\MetricsTimeseriesMetaDataDetails $metaData = null;

    /**
     * Retrieves breakdown values for a specified metric and timespan
     *
     * @var ?array<Components\MetricsBreakdownDetails> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\MetricsBreakdownDetails>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * The timeframe from and to details displayed in the form of unix epoch timestamps.
     *
     *
     *
     * @var ?array<int> $timespan
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('timespan')]
    #[\Speakeasy\Serializer\Annotation\Type('array<int>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $timespan = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\MetricsTimeseriesMetaDataDetails  $metaData
     * @param  ?array<Components\MetricsBreakdownDetails>  $data
     * @param  ?array<int>  $timespan
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\MetricsTimeseriesMetaDataDetails $metaData = null, ?array $data = null, ?array $timespan = null)
    {
        $this->success = $success;
        $this->metaData = $metaData;
        $this->data = $data;
        $this->timespan = $timespan;
    }
}