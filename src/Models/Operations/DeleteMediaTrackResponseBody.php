<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** DeleteMediaTrackResponseBody - Delete a video media */
class DeleteMediaTrackResponseBody
{
    /**
     * Demonstrates whether the request is successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * @param  ?bool  $success
     * @phpstan-pure
     */
    public function __construct(?bool $success = null)
    {
        $this->success = $success;
    }
}