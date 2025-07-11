<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Determines whether the recorded stream should be publicly accessible or private in Live to VOD (Video on Demand). */
enum MediaPolicy: string
{
    case Public = 'public';
    case Private = 'private';
}
