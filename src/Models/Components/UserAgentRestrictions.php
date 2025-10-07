<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** UserAgentRestrictions - Restrictions based on the user agent */
class UserAgentRestrictions
{
    /**
     * Policy action type
     *
     * @var ?PolicyAction $defaultPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('defaultPolicy')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PolicyAction|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PolicyAction $defaultPolicy = null;

    /**
     * A list of user agents that are explicitly allowed access
     *
     * @var ?array<string> $allow
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('allow')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $allow = null;

    /**
     * A list of user agents that are explicitly denied access
     *
     * @var ?array<string> $deny
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('deny')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $deny = null;

    /**
     * @param  ?PolicyAction  $defaultPolicy
     * @param  ?array<string>  $allow
     * @param  ?array<string>  $deny
     * @phpstan-pure
     */
    public function __construct(?PolicyAction $defaultPolicy = null, ?array $allow = null, ?array $deny = null)
    {
        $this->defaultPolicy = $defaultPolicy;
        $this->allow = $allow;
        $this->deny = $deny;
    }
}