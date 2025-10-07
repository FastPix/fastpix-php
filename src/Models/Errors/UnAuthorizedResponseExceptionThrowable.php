<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class UnAuthorizedResponseExceptionThrowable extends \RuntimeException
{
    public UnAuthorizedResponseException $container;

    public function __construct(string $message, int $statusCode, UnAuthorizedResponseException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}