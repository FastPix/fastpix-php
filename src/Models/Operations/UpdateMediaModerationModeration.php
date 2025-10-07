<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
class UpdateMediaModerationModeration
{
    /**
     * Type of media content
     *
     * @var ?Components\MediaType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaType|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\MediaType $type = null;

    /**
     * @param  ?Components\MediaType  $type
     * @phpstan-pure
     */
    public function __construct(?Components\MediaType $type = null)
    {
        $this->type = $type;
    }
}