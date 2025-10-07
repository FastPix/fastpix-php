<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class ChangeMediaOrderInPlaylistRequest
{
    /**
     * The unique id of the playlist you want to perform the operation on.
     *
     * @var string $playlistId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=playlistId')]
    public string $playlistId;

    /**
     *
     * @var Components\MediaIdsRequest $mediaIdsRequest
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public Components\MediaIdsRequest $mediaIdsRequest;

    /**
     * @param  string  $playlistId
     * @param  Components\MediaIdsRequest  $mediaIdsRequest
     * @phpstan-pure
     */
    public function __construct(string $playlistId, Components\MediaIdsRequest $mediaIdsRequest)
    {
        $this->playlistId = $playlistId;
        $this->mediaIdsRequest = $mediaIdsRequest;
    }
}