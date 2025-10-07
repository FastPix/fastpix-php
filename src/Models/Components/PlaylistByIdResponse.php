<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class PlaylistByIdResponse
{
    /**
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     *
     * @var ?PlaylistByIdResponseData $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PlaylistByIdResponseData|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PlaylistByIdResponseData $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?PlaylistByIdResponseData  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?PlaylistByIdResponseData $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}