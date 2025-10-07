<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Specifies the type of track (audio or subtitle). */
enum UpdateTrackResponseType: string
{
    case Audio = 'audio';
    case Subtitle = 'subtitle';
}
