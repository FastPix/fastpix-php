<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class DuplicateMp4SupportExceptionThrowable extends \RuntimeException
{
    public DuplicateMp4SupportException $container;

    public function __construct(string $message, int $statusCode, DuplicateMp4SupportException $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}