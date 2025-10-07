<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class Moderation
{
    /**
     * Type of media content
     *
     * @var MediaType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaType')]
    public MediaType $type;

    /**
     * @param  MediaType  $type
     * @phpstan-pure
     */
    public function __construct(MediaType $type)
    {
        $this->type = $type;
    }
}