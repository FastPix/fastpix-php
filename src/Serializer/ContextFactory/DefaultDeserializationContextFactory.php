<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\ContextFactory;

use FastPix\Sdk\Serializer\DeserializationContext;

/**
 * Default Deserialization Context Factory.
 */
final class DefaultDeserializationContextFactory implements DeserializationContextFactoryInterface
{
    public function createDeserializationContext(): DeserializationContext
    {
        return new DeserializationContext();
    }
}
