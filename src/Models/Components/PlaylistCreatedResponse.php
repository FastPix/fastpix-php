<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** PlaylistCreatedResponse - Displays the result of the request. */
class PlaylistCreatedResponse
{
    /**
     * It demonstrates whether the request is successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * Displays the result of the request.
     *
     * @var ?PlaylistCreatedSchema $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PlaylistCreatedSchema|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PlaylistCreatedSchema $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?PlaylistCreatedSchema  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?PlaylistCreatedSchema $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}