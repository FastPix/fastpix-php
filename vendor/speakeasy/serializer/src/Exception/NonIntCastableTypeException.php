<?php

declare(strict_types=1);

namespace Speakeasy\Serializer\Exception;

final class NonIntCastableTypeException extends NonCastableTypeException
{
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct('int', $value);
    }
}
