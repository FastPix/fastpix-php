<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
class CreateMediaPlaybackIdAccessRestrictions
{
    /**
     * Restrictions based on the originating domain of a request
     *
     * @var ?Components\DomainRestrictions $domains
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('domains')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\DomainRestrictions|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\DomainRestrictions $domains = null;

    /**
     * Restrictions based on the user agent
     *
     * @var ?Components\UserAgentRestrictions $userAgents
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('userAgents')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\UserAgentRestrictions|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\UserAgentRestrictions $userAgents = null;

    /**
     * @param  ?Components\DomainRestrictions  $domains
     * @param  ?Components\UserAgentRestrictions  $userAgents
     * @phpstan-pure
     */
    public function __construct(?Components\DomainRestrictions $domains = null, ?Components\UserAgentRestrictions $userAgents = null)
    {
        $this->domains = $domains;
        $this->userAgents = $userAgents;
    }
}