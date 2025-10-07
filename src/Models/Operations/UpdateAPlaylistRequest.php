<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class UpdateAPlaylistRequest
{
    /**
     * The unique id of the playlist you want to retrieve.
     *
     * @var string $playlistId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=playlistId')]
    public string $playlistId;

    /**
     *
     * @var Components\UpdatePlaylistRequest $updatePlaylistRequest
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public Components\UpdatePlaylistRequest $updatePlaylistRequest;

    /**
     * @param  string  $playlistId
     * @param  Components\UpdatePlaylistRequest  $updatePlaylistRequest
     * @phpstan-pure
     */
    public function __construct(string $playlistId, Components\UpdatePlaylistRequest $updatePlaylistRequest)
    {
        $this->playlistId = $playlistId;
        $this->updatePlaylistRequest = $updatePlaylistRequest;
    }
}