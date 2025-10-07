<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Type will be either smart or manual, as sent in the request body. */
enum PlaylistCreatedSchemaType: string
{
    case Smart = 'smart';
    case Manual = 'manual';
}
