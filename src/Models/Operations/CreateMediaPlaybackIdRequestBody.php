<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** CreateMediaPlaybackIdRequestBody - Request body for creating playback id for an media */
class CreateMediaPlaybackIdRequestBody
{
    /**
     * Access policy for media content
     *
     * @var Components\AccessPolicy $accessPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessPolicy')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\AccessPolicy')]
    public Components\AccessPolicy $accessPolicy;

    /**
     *
     * @var ?CreateMediaPlaybackIdAccessRestrictions $accessRestrictions
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessRestrictions')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\CreateMediaPlaybackIdAccessRestrictions|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?CreateMediaPlaybackIdAccessRestrictions $accessRestrictions = null;

    /**
     * DRM configuration ID (required if accessPolicy is 'drm')
     *
     * @var ?string $drmConfigurationId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('drmConfigurationId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $drmConfigurationId = null;

    /**
     * The maximum resolution for the playback ID.
     *
     * @var ?Resolution $resolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('resolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\Resolution|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Resolution $resolution = null;

    /**
     * @param  Components\AccessPolicy  $accessPolicy
     * @param  ?CreateMediaPlaybackIdAccessRestrictions  $accessRestrictions
     * @param  ?string  $drmConfigurationId
     * @param  ?Resolution  $resolution
     * @phpstan-pure
     */
    public function __construct(Components\AccessPolicy $accessPolicy, ?CreateMediaPlaybackIdAccessRestrictions $accessRestrictions = null, ?string $drmConfigurationId = null, ?Resolution $resolution = null)
    {
        $this->accessPolicy = $accessPolicy;
        $this->accessRestrictions = $accessRestrictions;
        $this->drmConfigurationId = $drmConfigurationId;
        $this->resolution = $resolution;
    }
}