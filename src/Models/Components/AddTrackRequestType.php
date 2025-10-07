<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Specifies the type of track being added. It can be either `audio` or `subtitle`. */
enum AddTrackRequestType: string
{
    case Audio = 'audio';
    case Subtitle = 'subtitle';
}
