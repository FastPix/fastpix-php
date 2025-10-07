<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
class UpdateAPlaylistResponse
{
    /**
     * HTTP response content type for this operation
     *
     * @var string $contentType
     */
    public string $contentType;

    /**
     * HTTP response status code for this operation
     *
     * @var int $statusCode
     */
    public int $statusCode;

    /**
     * Raw HTTP response; suitable for custom response parsing
     *
     * @var \Psr\Http\Message\ResponseInterface $rawResponse
     */
    public \Psr\Http\Message\ResponseInterface $rawResponse;

    /**
     * Playlist updated successfully
     *
     * @var ?Components\PlaylistCreatedResponse $playlistCreatedResponse
     */
    public ?Components\PlaylistCreatedResponse $playlistCreatedResponse = null;

    /**
     * @param  string  $contentType
     * @param  int  $statusCode
     * @param  \Psr\Http\Message\ResponseInterface  $rawResponse
     * @param  ?Components\PlaylistCreatedResponse  $playlistCreatedResponse
     * @phpstan-pure
     */
    public function __construct(string $contentType, int $statusCode, \Psr\Http\Message\ResponseInterface $rawResponse, ?Components\PlaylistCreatedResponse $playlistCreatedResponse = null)
    {
        $this->contentType = $contentType;
        $this->statusCode = $statusCode;
        $this->rawResponse = $rawResponse;
        $this->playlistCreatedResponse = $playlistCreatedResponse;
    }
}