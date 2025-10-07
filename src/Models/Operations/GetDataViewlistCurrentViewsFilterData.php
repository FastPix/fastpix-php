<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class GetDataViewlistCurrentViewsFilterData
{
    /**
     * Number of concurrent viewers for this dimension value.
     *
     * @var ?int $concurrentViewers
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('concurrent_viewers')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $concurrentViewers = null;

    /**
     * Name of the dimension (e.g., country, device type, etc.).
     *
     * @var ?string $dimensionName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('dimension_name')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $dimensionName = null;

    /**
     * Additional metric value for this dimension (if applicable).
     *
     * @var ?int $metricValue
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metricValue')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $metricValue = null;

    /**
     * @param  ?int  $concurrentViewers
     * @param  ?string  $dimensionName
     * @param  ?int  $metricValue
     * @phpstan-pure
     */
    public function __construct(?int $concurrentViewers = null, ?string $dimensionName = null, ?int $metricValue = null)
    {
        $this->concurrentViewers = $concurrentViewers;
        $this->dimensionName = $dimensionName;
        $this->metricValue = $metricValue;
    }
}