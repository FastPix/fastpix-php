<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class CreateMediaRequestDomains
{
    /**
     * Specifies the default access policy for domains. 
     *
     * If set to `allow`, all domains are allowed access unless otherwise specified in the `deny` lists. 
     * If set to `deny`, all domains are denied access unless otherwise specified in the `allow` lists.
     *
     *
     * @var ?CreateMediaRequestDomainsDefaultPolicy $defaultPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('defaultPolicy')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreateMediaRequestDomainsDefaultPolicy|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?CreateMediaRequestDomainsDefaultPolicy $defaultPolicy = null;

    /**
     * A list of domain names or patterns that are explicitly allowed access. 
     *
     * This list is only effective when the `defaultPolicy` is set to `deny`.
     *
     *
     * @var ?array<string> $allow
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('allow')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $allow = null;

    /**
     * A list of domain names or patterns that are explicitly denied access. 
     *
     * This list is only effective when the `defaultPolicy` is set to `allow`.
     *
     *
     * @var ?array<string> $deny
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('deny')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $deny = null;

    /**
     * @param  ?CreateMediaRequestDomainsDefaultPolicy  $defaultPolicy
     * @param  ?array<string>  $allow
     * @param  ?array<string>  $deny
     * @phpstan-pure
     */
    public function __construct(?CreateMediaRequestDomainsDefaultPolicy $defaultPolicy = null, ?array $allow = null, ?array $deny = null)
    {
        $this->defaultPolicy = $defaultPolicy;
        $this->allow = $allow;
        $this->deny = $deny;
    }
}