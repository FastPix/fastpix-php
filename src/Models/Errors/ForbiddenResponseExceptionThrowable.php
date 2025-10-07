<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class ForbiddenResponseExceptionThrowable extends \RuntimeException
{
    public ForbiddenResponseException $container;

    public function __construct(string $message, int $statusCode, ForbiddenResponseException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}