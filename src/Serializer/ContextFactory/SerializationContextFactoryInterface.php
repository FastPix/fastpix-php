<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\ContextFactory;

use FastPix\Sdk\Serializer\SerializationContext;

/**
 * Serialization Context Factory Interface.
 */
interface SerializationContextFactoryInterface
{
    public function createSerializationContext(): SerializationContext;
}
