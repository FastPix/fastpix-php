<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class ViewsCountResponse
{
    /**
     * Indicates whether the request was successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * Contains the view count details.
     *
     * @var ?ViewsCountResponseData $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\ViewsCountResponseData|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?ViewsCountResponseData $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?ViewsCountResponseData  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?ViewsCountResponseData $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}