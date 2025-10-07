<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** ListOverallValuesResponseBody - Get filter/ dimension value details by dimension name. */
class ListOverallValuesResponseBody
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
     * Metadata that has to be paased for metric calculations.
     *
     * @var ?Components\MetricsOverallMetaDataDetails $metaData
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metaData')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MetricsOverallMetaDataDetails|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\MetricsOverallMetaDataDetails $metaData = null;

    /**
     * Retrieves overall values for a specified metric
     *
     * @var ?Components\MetricsOverallDataDetails $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MetricsOverallDataDetails|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\MetricsOverallDataDetails $data = null;

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
     * @param  ?Components\MetricsOverallMetaDataDetails  $metaData
     * @param  ?Components\MetricsOverallDataDetails  $data
     * @param  ?array<int>  $timespan
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\MetricsOverallMetaDataDetails $metaData = null, ?Components\MetricsOverallDataDetails $data = null, ?array $timespan = null)
    {
        $this->success = $success;
        $this->metaData = $metaData;
        $this->data = $data;
        $this->timespan = $timespan;
    }
}