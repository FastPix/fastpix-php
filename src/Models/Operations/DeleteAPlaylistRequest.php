<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class DeleteAPlaylistRequest
{
    /**
     * The unique id of the playlist you want to delete.
     *
     * @var string $playlistId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=playlistId')]
    public string $playlistId;

    /**
     * @param  string  $playlistId
     * @phpstan-pure
     */
    public function __construct(string $playlistId)
    {
        $this->playlistId = $playlistId;
    }
}