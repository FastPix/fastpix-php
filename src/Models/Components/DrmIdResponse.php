<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class DrmIdResponse
{
    /**
     * The unique identifier of the DRM configuration.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * @param  ?string  $id
     * @phpstan-pure
     */
    public function __construct(?string $id = null)
    {
        $this->id = $id;
    }
}