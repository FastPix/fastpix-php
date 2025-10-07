<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class TrackDuplicateRequestExceptionThrowable extends \RuntimeException
{
    public TrackDuplicateRequestException $container;

    public function __construct(string $message, int $statusCode, TrackDuplicateRequestException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}