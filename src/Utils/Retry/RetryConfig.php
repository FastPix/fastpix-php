<?php



declare(strict_types=1);

namespace FastPix\Sdk\Utils\Retry;

abstract class RetryConfig
{
    public RetryStrategy $strategy;
}
