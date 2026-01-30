<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\ContextFactory;

use FastPix\Sdk\Serializer\SerializationContext;

/**
 * Default Serialization Context Factory.
 */
final class DefaultSerializationContextFactory implements SerializationContextFactoryInterface
{
    public function createSerializationContext(): SerializationContext
    {
        return new SerializationContext();
    }
}
