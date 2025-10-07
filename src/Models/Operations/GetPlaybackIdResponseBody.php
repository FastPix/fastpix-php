<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** GetPlaybackIdResponseBody - Successfully retrieved playback ID details */
class GetPlaybackIdResponseBody
{
    /**
     * Indicates if the request was successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     *
     * @var ?GetPlaybackIdData $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\GetPlaybackIdData|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?GetPlaybackIdData $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?GetPlaybackIdData  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?GetPlaybackIdData $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}