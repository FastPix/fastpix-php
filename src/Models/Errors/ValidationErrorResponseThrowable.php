<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class ValidationErrorResponseThrowable extends \RuntimeException
{
    public ValidationErrorResponse $container;

    public function __construct(string $message, int $statusCode, ValidationErrorResponse $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}