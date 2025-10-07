<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** MediaIdsRequest - The list of mediaId(s) you want to perform the operation on.rds by limit. */
class MediaIdsRequest
{
    /**
     * $mediaIds
     *
     * @var array<string> $mediaIds
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaIds')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>')]
    public array $mediaIds;

    /**
     * @param  array<string>  $mediaIds
     * @phpstan-pure
     */
    public function __construct(array $mediaIds)
    {
        $this->mediaIds = $mediaIds;
    }
}