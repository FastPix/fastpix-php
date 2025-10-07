<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Access policy for media content */
enum AccessPolicy: string
{
    case Public = 'public';
    case Private = 'private';
    case Drm = 'drm';
}
