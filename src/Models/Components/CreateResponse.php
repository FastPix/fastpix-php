<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class CreateResponse
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
     * @var ?CreateSigningKeyResponseDTO $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreateSigningKeyResponseDTO|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?CreateSigningKeyResponseDTO $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?CreateSigningKeyResponseDTO  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?CreateSigningKeyResponseDTO $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}