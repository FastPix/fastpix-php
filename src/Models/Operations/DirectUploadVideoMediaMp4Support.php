<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/**
 * Generates MP4 video up to 4K ("capped_4k"), m4a audio only ("audioOnly"), or both for offline viewing.
 *
 *
 */
enum DirectUploadVideoMediaMp4Support: string
{
    case Capped4k = 'capped_4k';
    case AudioOnly = 'audioOnly';
    case AudioOnlyCapped4k = 'audioOnly,capped_4k';
}
