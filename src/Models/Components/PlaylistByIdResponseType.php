<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** type of the playlist, when it was created */
enum PlaylistByIdResponseType: string
{
    case Manual = 'manual';
    case Smart = 'smart';
}
