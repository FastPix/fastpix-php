<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Type of overlay (currently only supports 'audio'). */
enum AudioInputType: string
{
    case Audio = 'audio';
}
