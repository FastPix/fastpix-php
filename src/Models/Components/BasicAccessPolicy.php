<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Basic access policy for media content */
enum BasicAccessPolicy: string
{
    case Public = 'public';
    case Private = 'private';
}
