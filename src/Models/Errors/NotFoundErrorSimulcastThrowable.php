<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class NotFoundErrorSimulcastThrowable extends \RuntimeException
{
    public NotFoundErrorSimulcast $container;

    public function __construct(string $message, int $statusCode, NotFoundErrorSimulcast $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}