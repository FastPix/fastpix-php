<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class InvalidPlaylistIdResponseExceptionThrowable extends \RuntimeException
{
    public InvalidPlaylistIdResponseException $container;

    public function __construct(string $message, int $statusCode, InvalidPlaylistIdResponseException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}