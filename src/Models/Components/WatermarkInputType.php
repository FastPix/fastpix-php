<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Type of overlay (currently only supports 'watermark'). */
enum WatermarkInputType: string
{
    case Watermark = 'watermark';
}
