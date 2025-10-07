<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class Item
{
    /**
     * The specific metric value calculated based on the applied filters.
     *
     * @var ?int $value
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('value')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $value = null;

    /**
     * value can be score that ranges from 0 to 100
     *
     * @var ?string $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $type = null;

    /**
     * value can be score that ranges from 0 to 100
     *
     * @var ?string $name
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('name')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $name = null;

    /**
     * The metric field represents the name of the Key Performance Indicator (KPI) being tracked or analyzed. It identifies a specific measurable aspect of the video playback experience, such as buffering time, video start failure rate, or playback quality.
     *
     *
     *
     * @var ?string $metric
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metric')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $metric = null;

    /**
     * value can be avg, sum, count or 95th
     *
     * @var ?string $measurement
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('measurement')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $measurement = null;

    /**
     * @param  ?int  $value
     * @param  ?string  $type
     * @param  ?string  $name
     * @param  ?string  $metric
     * @param  ?string  $measurement
     * @phpstan-pure
     */
    public function __construct(?int $value = null, ?string $type = null, ?string $name = null, ?string $metric = null, ?string $measurement = null)
    {
        $this->value = $value;
        $this->type = $type;
        $this->name = $name;
        $this->metric = $metric;
        $this->measurement = $measurement;
    }
}