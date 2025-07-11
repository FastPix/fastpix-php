<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** Determines if access to the streamed content is kept private or available to all. */
enum CreateMediaPlaybackIdAccessPolicy: string
{
    case Public = 'public';
    case Private = 'private';
    case Drm = 'drm';
}
