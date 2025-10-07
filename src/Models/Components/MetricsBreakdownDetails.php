<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class MetricsBreakdownDetails
{
    /**
     * The specific metric value calculated based on the applied filters.
     *
     * @var int|float|null $value
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('value')]
    #[\Speakeasy\Serializer\Annotation\Type('int|float|null')]
    public int|float|null $value;

    /**
     * Total count of view sessions for a paricular video content.
     *
     * @var ?int $views
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('views')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $views = null;

    /**
     * Total time watched across all views, represented in milliseconds.
     *
     * @var ?int $totalWatchTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalWatchTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalWatchTime = null;

    /**
     * Total time spent playing the video, represented in milliseconds.
     *
     * @var ?int $totalPlayingTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalPlayingTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalPlayingTime = null;

    /**
     * the value of dimension or filter value on which the aggregation is to be applied.
     *
     * @var ?string $field
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('field')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $field = null;

    /**
     * @param  int|float|null  $value
     * @param  ?int  $views
     * @param  ?int  $totalWatchTime
     * @param  ?int  $totalPlayingTime
     * @param  ?string  $field
     * @phpstan-pure
     */
    public function __construct(int|float|null $value = null, ?int $views = null, ?int $totalWatchTime = null, ?int $totalPlayingTime = null, ?string $field = null)
    {
        $this->value = $value;
        $this->views = $views;
        $this->totalWatchTime = $totalWatchTime;
        $this->totalPlayingTime = $totalPlayingTime;
        $this->field = $field;
    }
}