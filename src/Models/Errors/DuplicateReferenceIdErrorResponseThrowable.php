<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class DuplicateReferenceIdErrorResponseThrowable extends \RuntimeException
{
    public DuplicateReferenceIdErrorResponse $container;

    public function __construct(string $message, int $statusCode, DuplicateReferenceIdErrorResponse $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}