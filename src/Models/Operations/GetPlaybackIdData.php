<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
class GetPlaybackIdData
{
    /**
     * The unique identifier for the playback ID.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * Access policy for media content
     *
     * @var ?Components\AccessPolicy $accessPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessPolicy')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\AccessPolicy|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\AccessPolicy $accessPolicy = null;

    /**
     * @param  ?string  $id
     * @param  ?Components\AccessPolicy  $accessPolicy
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?Components\AccessPolicy $accessPolicy = null)
    {
        $this->id = $id;
        $this->accessPolicy = $accessPolicy;
    }
}