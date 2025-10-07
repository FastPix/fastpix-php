<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Determines the insertion order of media into playlist. */
enum PlaylistOrder: string
{
    case CreatedDateASC = 'createdDate ASC';
    case CreatedDateDESC = 'createdDate DESC';
}
