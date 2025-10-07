<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** MetricsOverallDataDetails - Retrieves overall values for a specified metric */
class MetricsOverallDataDetails
{
    /**
     * metric value calculated based on the applied filters.
     *
     * @var int|float|null $value
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('value')]
    #[\Speakeasy\Serializer\Annotation\Type('int|float|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public int|float|null $value = null;

    /**
     * Total time watched across all views, represented in milliseconds.
     *
     * @var ?int $totalWatchTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalWatchTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalWatchTime = null;

    /**
     * The count of unique viewers who interacted with the content.
     *
     * @var ?int $uniqueViews
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('uniqueViews')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $uniqueViews = null;

    /**
     * The total number of views recorded.
     *
     * @var ?int $totalViews
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalViews')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalViews = null;

    /**
     * Total time spent playing the video, represented in milliseconds.
     *
     * @var ?int $totalPlayTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalPlayTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalPlayTime = null;

    /**
     * A global metric value that reflects the overall performance of the specified metric across the entire dataset for the given timespan.
     *
     * @var int|float|null $globalValue
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('globalValue')]
    #[\Speakeasy\Serializer\Annotation\Type('int|float|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public int|float|null $globalValue = null;

    /**
     * @param  int|float|null  $value
     * @param  ?int  $totalWatchTime
     * @param  ?int  $uniqueViews
     * @param  ?int  $totalViews
     * @param  ?int  $totalPlayTime
     * @param  int|float|null  $globalValue
     * @phpstan-pure
     */
    public function __construct(int|float|null $value = null, ?int $totalWatchTime = null, ?int $uniqueViews = null, ?int $totalViews = null, ?int $totalPlayTime = null, int|float|null $globalValue = null)
    {
        $this->value = $value;
        $this->totalWatchTime = $totalWatchTime;
        $this->uniqueViews = $uniqueViews;
        $this->totalViews = $totalViews;
        $this->totalPlayTime = $totalPlayTime;
        $this->globalValue = $globalValue;
    }
}