<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class LiveNotFoundErrorThrowable extends \RuntimeException
{
    public LiveNotFoundError $container;

    public function __construct(string $message, int $statusCode, LiveNotFoundError $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}