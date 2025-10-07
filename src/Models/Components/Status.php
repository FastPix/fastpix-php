<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** The current processing status of the media. */
enum Status: string
{
    case Preparing = 'preparing';
    case Ready = 'ready';
    case Failed = 'failed';
    case Created = 'created';
}
