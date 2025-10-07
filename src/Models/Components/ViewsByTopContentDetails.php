<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** ViewsByTopContentDetails - Retrieves a list of the top video views */
class ViewsByTopContentDetails
{
    /**
     * Title of the video
     *
     * @var ?string $videoTitle
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoTitle')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoTitle = null;

    /**
     * Total count of view sessions for a paricular video content.
     *
     * @var ?int $views
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('views')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $views = null;

    /**
     * Total count of unique video viewers for particular video content.
     *
     * @var ?int $uniqueViews
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('uniqueViews')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $uniqueViews = null;

    /**
     * @param  ?string  $videoTitle
     * @param  ?int  $views
     * @param  ?int  $uniqueViews
     * @phpstan-pure
     */
    public function __construct(?string $videoTitle = null, ?int $views = null, ?int $uniqueViews = null)
    {
        $this->videoTitle = $videoTitle;
        $this->views = $views;
        $this->uniqueViews = $uniqueViews;
    }
}