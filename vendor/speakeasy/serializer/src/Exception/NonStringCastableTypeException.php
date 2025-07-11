<?php

declare(strict_types=1);

namespace Speakeasy\Serializer\Exception;

final class NonStringCastableTypeException extends NonCastableTypeException
{
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct('string', $value);
    }
}
