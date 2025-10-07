<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Type of media content */
enum MediaType: string
{
    case Video = 'video';
    case Audio = 'audio';
    case Av = 'av';
}
