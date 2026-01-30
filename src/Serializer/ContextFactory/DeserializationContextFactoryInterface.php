<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\ContextFactory;

use FastPix\Sdk\Serializer\DeserializationContext;

/**
 * Deserialization Context Factory Interface.
 */
interface DeserializationContextFactoryInterface
{
    public function createDeserializationContext(): DeserializationContext;
}
