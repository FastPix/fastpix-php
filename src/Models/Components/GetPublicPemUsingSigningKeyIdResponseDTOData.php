<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** GetPublicPemUsingSigningKeyIdResponseDTOData - Displays the result of the request. */
class GetPublicPemUsingSigningKeyIdResponseDTOData
{
    /**
     * FastPix generates a unique identifier for each workspace.
     *
     * @var ?string $workspaceId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('workspaceId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $workspaceId = null;

    /**
     *
     * @var ?string $signingKeyId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('signingKeyId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $signingKeyId = null;

    /**
     * A public key is a byte encoded key used to create a signed JSON Web Token (JWT) for authentication.
     *
     * @var ?string $publicKey
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('publicKey')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $publicKey = null;

    /**
     * @param  ?string  $workspaceId
     * @param  ?string  $signingKeyId
     * @param  ?string  $publicKey
     * @phpstan-pure
     */
    public function __construct(?string $workspaceId = null, ?string $signingKeyId = null, ?string $publicKey = null)
    {
        $this->workspaceId = $workspaceId;
        $this->signingKeyId = $signingKeyId;
        $this->publicKey = $publicKey;
    }
}