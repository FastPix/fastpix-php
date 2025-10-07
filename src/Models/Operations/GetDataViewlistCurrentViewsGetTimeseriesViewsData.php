<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class GetDataViewlistCurrentViewsGetTimeseriesViewsData
{
    /**
     * The timestamp for the interval (ISO 8601 format).
     *
     * @var ?\DateTime $intervalTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('intervalTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $intervalTime = null;

    /**
     * Number of concurrent viewers at the given interval.
     *
     * @var ?int $numberOfViews
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('numberOfViews')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $numberOfViews = null;

    /**
     * Reserved for future metric values (currently null).
     *
     * @var ?int $metricValue
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metricValue')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $metricValue = null;

    /**
     * @param  ?\DateTime  $intervalTime
     * @param  ?int  $numberOfViews
     * @param  ?int  $metricValue
     * @phpstan-pure
     */
    public function __construct(?\DateTime $intervalTime = null, ?int $numberOfViews = null, ?int $metricValue = null)
    {
        $this->intervalTime = $intervalTime;
        $this->numberOfViews = $numberOfViews;
        $this->metricValue = $metricValue;
    }
}