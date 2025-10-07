<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class StreamAlreadyDisabledErrorThrowable extends \RuntimeException
{
    public StreamAlreadyDisabledError $container;

    public function __construct(string $message, int $statusCode, StreamAlreadyDisabledError $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}