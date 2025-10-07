<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class StreamAlreadyEnabledErrorThrowable extends \RuntimeException
{
    public StreamAlreadyEnabledError $container;

    public function __construct(string $message, int $statusCode, StreamAlreadyEnabledError $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}