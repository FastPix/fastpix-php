<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** PlaybackIdSuccessResponse - Displays the result of the request. */
class PlaybackIdSuccessResponse
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
     *
     * @var ?PlaybackIdSuccessResponseData $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PlaybackIdSuccessResponseData|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PlaybackIdSuccessResponseData $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?PlaybackIdSuccessResponseData  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?PlaybackIdSuccessResponseData $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}