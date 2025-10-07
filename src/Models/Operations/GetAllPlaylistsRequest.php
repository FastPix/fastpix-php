<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetAllPlaylistsRequest
{
    /**
     * The number of playlists to return (default is 10, max is 50).
     *
     * @var ?int $limit
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=limit')]
    public ?int $limit = null;

    /**
     * The page number to retrieve, starting from 1. Used for paginating the playlist results.
     *
     * @var ?int $offset
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=offset')]
    public ?int $offset = null;

    /**
     * @param  ?int  $limit
     * @param  ?int  $offset
     * @phpstan-pure
     */
    public function __construct(?int $limit = 10, ?int $offset = 1)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }
}