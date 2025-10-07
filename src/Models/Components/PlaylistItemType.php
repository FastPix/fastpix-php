<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


enum PlaylistItemType: string
{
    case Manual = 'manual';
    case Smart = 'smart';
}
