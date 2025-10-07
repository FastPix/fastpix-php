<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class UpdatePlaylistRequest
{
    /**
     * New name to the playlist.
     *
     * @var string $name
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('name')]
    public string $name;

    /**
     * Updated description to the playlist.
     *
     * @var string $description
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('description')]
    public string $description;

    /**
     * @param  string  $name
     * @param  string  $description
     * @phpstan-pure
     */
    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }
}