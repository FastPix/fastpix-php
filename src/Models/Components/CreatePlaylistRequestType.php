<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** For a smart playlist metadata is required. */
enum CreatePlaylistRequestType: string
{
    case Smart = 'smart';
    case Manual = 'manual';
}
