<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** RetrieveMediaInputInfoResponseBody - Get video media input information */
class RetrieveMediaInputInfoResponseBody
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
     * Displays the result of the request.
     *
     * @var ?RetrieveMediaInputInfoData $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\RetrieveMediaInputInfoData|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?RetrieveMediaInputInfoData $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?RetrieveMediaInputInfoData  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?RetrieveMediaInputInfoData $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}