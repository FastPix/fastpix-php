<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class SigningKeyNotFoundErrorThrowable extends \RuntimeException
{
    public SigningKeyNotFoundError $container;

    public function __construct(string $message, int $statusCode, SigningKeyNotFoundError $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}