<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class MediaClipResponseAccessRestrictions
{
    /**
     *
     * @var ?MediaClipResponseDomains $domains
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('domains')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaClipResponseDomains|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?MediaClipResponseDomains $domains = null;

    /**
     *
     * @var ?MediaClipResponseUserAgents $userAgents
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('userAgents')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaClipResponseUserAgents|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?MediaClipResponseUserAgents $userAgents = null;

    /**
     * @param  ?MediaClipResponseDomains  $domains
     * @param  ?MediaClipResponseUserAgents  $userAgents
     * @phpstan-pure
     */
    public function __construct(?MediaClipResponseDomains $domains = null, ?MediaClipResponseUserAgents $userAgents = null)
    {
        $this->domains = $domains;
        $this->userAgents = $userAgents;
    }
}