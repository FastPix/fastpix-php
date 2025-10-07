<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class GetAllSigningKeyResponse
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
     * @var ?array<GetAllSigningKeyResponseDTO> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\GetAllSigningKeyResponseDTO>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?array<GetAllSigningKeyResponseDTO>  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}