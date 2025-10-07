<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class UnauthorizedExceptionThrowable extends \RuntimeException
{
    public UnauthorizedException $container;

    public function __construct(string $message, int $statusCode, UnauthorizedException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}