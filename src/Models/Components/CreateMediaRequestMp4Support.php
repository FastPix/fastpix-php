<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/**
 * “capped_4k": Generates an mp4 video file up to 4k resolution "audioOnly": Generates an m4a audio file of the media file "audioOnly,capped_4k": Generates both video and audio media files for offline viewing
 *
 *
 */
enum CreateMediaRequestMp4Support: string
{
    case Capped4k = 'capped_4k';
    case AudioOnly = 'audioOnly';
    case AudioOnlyCapped4k = 'audioOnly,capped_4k';
}
