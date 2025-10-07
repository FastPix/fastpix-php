<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** ListComparisonValuesResponseBody - Get filter/ dimension value details by dimension name. */
class ListComparisonValuesResponseBody
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
     * Displays the result of the request.
     *
     *
     *
     * @var ?array<array<Components\MetricsComparisonDetails>> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<array<\FastPix\Sdk\Models\Components\MetricsComparisonDetails>>|null')]
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
     * @param  ?array<array<Components\MetricsComparisonDetails>>  $data
     * @param  ?array<int>  $timespan
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null, ?array $timespan = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->timespan = $timespan;
    }
}