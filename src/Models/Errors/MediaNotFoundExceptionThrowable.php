<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class MediaNotFoundExceptionThrowable extends \RuntimeException
{
    public MediaNotFoundException $container;

    public function __construct(string $message, int $statusCode, MediaNotFoundException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}