<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** MetricsTimeseriesDataDetails - The metric's value at specific time intervals. */
class MetricsTimeseriesDataDetails
{
    /**
     * The timestamp for the data point indicating when the metric value was recorded.
     *
     * @var ?\DateTime $intervalTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('intervalTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $intervalTime = null;

    /**
     * The value of the specified metric at the given interval.
     *
     * @var int|float|null $metricValue
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metricValue')]
    #[\Speakeasy\Serializer\Annotation\Type('int|float|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public int|float|null $metricValue = null;

    /**
     * The total number of views recorded during that interval.
     *
     * @var ?int $numberOfViews
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('numberOfViews')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $numberOfViews = null;

    /**
     * @param  ?\DateTime  $intervalTime
     * @param  int|float|null  $metricValue
     * @param  ?int  $numberOfViews
     * @phpstan-pure
     */
    public function __construct(?\DateTime $intervalTime = null, int|float|null $metricValue = null, ?int $numberOfViews = null)
    {
        $this->intervalTime = $intervalTime;
        $this->metricValue = $metricValue;
        $this->numberOfViews = $numberOfViews;
    }
}