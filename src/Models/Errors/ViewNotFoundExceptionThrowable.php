<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class ViewNotFoundExceptionThrowable extends \RuntimeException
{
    public ViewNotFoundException $container;

    public function __construct(string $message, int $statusCode, ViewNotFoundException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}