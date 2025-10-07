<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Horizontal alignment of the watermark. */
enum XAlign: string
{
    case Left = 'left';
    case Center = 'center';
    case Right = 'right';
}
