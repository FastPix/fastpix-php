<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/**
 * Determines whether access to the streamed content is kept private or available to all.
 *
 *
 */
enum CreateMediaRequestAccessPolicy: string
{
    case Public = 'public';
    case Private = 'private';
    case Drm = 'drm';
}
