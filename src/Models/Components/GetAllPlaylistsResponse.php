<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class GetAllPlaylistsResponse
{
    /**
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * $data
     *
     * @var ?array<PlaylistItem> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\PlaylistItem>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * Pagination organizes content into pages for better readability and navigation.
     *
     * @var ?Pagination $pagination
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pagination')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\Pagination|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Pagination $pagination = null;

    /**
     * @param  ?bool  $success
     * @param  ?array<PlaylistItem>  $data
     * @param  ?Pagination  $pagination
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null, ?Pagination $pagination = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->pagination = $pagination;
    }
}