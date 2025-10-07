<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class CreateMediaRequestUserAgents
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
     * A list of user agents (identified by string names or patterns) that are explicitly allowed access. 
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
     * A list of user agents (identified by string names or patterns) that are explicitly denied access. 
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