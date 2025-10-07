<?php




declare(strict_types=1);

namespace FastPix\Sdk\Utils\Retry;

enum RetryStrategy: string
{
    case NONE = 'none';
    case BACKOFF = 'backoff';
}
