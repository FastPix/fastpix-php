<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class InvalidPermissionExceptionThrowable extends \RuntimeException
{
    public InvalidPermissionException $container;

    public function __construct(string $message, int $statusCode, InvalidPermissionException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}