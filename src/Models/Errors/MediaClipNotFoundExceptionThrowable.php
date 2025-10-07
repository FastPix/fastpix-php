<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class MediaClipNotFoundExceptionThrowable extends \RuntimeException
{
    public MediaClipNotFoundException $container;

    public function __construct(string $message, int $statusCode, MediaClipNotFoundException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}