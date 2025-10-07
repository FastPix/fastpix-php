<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** The type of media track. */
enum MediaClipResponseType: string
{
    case Video = 'video';
    case Audio = 'audio';
    case Subtitle = 'subtitle';
}
