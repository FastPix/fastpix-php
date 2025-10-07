<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class MediaClipResponseUserAgents
{
    /**
     *
     * @var ?string $defaultPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('defaultPolicy')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $defaultPolicy = null;

    /**
     * $allow
     *
     * @var ?array<string> $allow
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('allow')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $allow = null;

    /**
     * $deny
     *
     * @var ?array<string> $deny
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('deny')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $deny = null;

    /**
     * @param  ?string  $defaultPolicy
     * @param  ?array<string>  $allow
     * @param  ?array<string>  $deny
     * @phpstan-pure
     */
    public function __construct(?string $defaultPolicy = null, ?array $allow = null, ?array $deny = null)
    {
        $this->defaultPolicy = $defaultPolicy;
        $this->allow = $allow;
        $this->deny = $deny;
    }
}