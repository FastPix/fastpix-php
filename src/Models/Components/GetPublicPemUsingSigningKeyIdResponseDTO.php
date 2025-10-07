<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** GetPublicPemUsingSigningKeyIdResponseDTO - Displays the result of the request. */
class GetPublicPemUsingSigningKeyIdResponseDTO
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
     * @var ?GetPublicPemUsingSigningKeyIdResponseDTOData $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\GetPublicPemUsingSigningKeyIdResponseDTOData|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?GetPublicPemUsingSigningKeyIdResponseDTOData $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?GetPublicPemUsingSigningKeyIdResponseDTOData  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?GetPublicPemUsingSigningKeyIdResponseDTOData $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}