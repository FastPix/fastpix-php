<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class SimulcastUpdateRequest
{
    /**
     * When the value is set to false, the simulcast will be disabled for the given stream.
     *
     * @var ?bool $isEnabled
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('isEnabled')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $isEnabled = null;

    /**
     * You can search for videos with specific key value pairs using metadata, when you tag a video in "key":"value"s pairs. Dynamic Metadata allows you to define a key that allows any value pair. You can have maximum of 255 characters and upto 10 entries are allowed.
     *
     * @var ?array<string, string> $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string, string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $metadata = null;

    /**
     * @param  ?bool  $isEnabled
     * @param  ?array<string, string>  $metadata
     * @phpstan-pure
     */
    public function __construct(?bool $isEnabled = null, ?array $metadata = null)
    {
        $this->isEnabled = $isEnabled;
        $this->metadata = $metadata;
    }
}