<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** ViewsCountResponseData - Contains the view count details. */
class ViewsCountResponseData
{
    /**
     * Number of views for the stream or resource.
     *
     * @var ?int $views
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('views')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $views = null;

    /**
     * @param  ?int  $views
     * @phpstan-pure
     */
    public function __construct(?int $views = null)
    {
        $this->views = $views;
    }
}