<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class UpdatedMediaRequestBody
{
    /**
     * $metadata
     *
     * @var ?array<string, string> $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string, string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $metadata = null;

    /**
     * @param  ?array<string, string>  $metadata
     * @phpstan-pure
     */
    public function __construct(?array $metadata = null)
    {
        $this->metadata = $metadata;
    }
}