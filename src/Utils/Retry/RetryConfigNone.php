<?php




declare(strict_types=1);

namespace FastPix\Sdk\Utils\Retry;

class RetryConfigNone extends RetryConfig
{
    public RetryStrategy $strategy = RetryStrategy::NONE;
}
