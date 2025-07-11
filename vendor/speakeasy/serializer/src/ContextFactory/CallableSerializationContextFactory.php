<?php

declare(strict_types=1);

namespace Speakeasy\Serializer\ContextFactory;

use Speakeasy\Serializer\SerializationContext;

/**
 * Serialization Context Factory using a callable.
 */
final class CallableSerializationContextFactory implements SerializationContextFactoryInterface
{
    /**
     * @var callable():SerializationContext
     */
    private $callable;

    /**
     * @param callable():SerializationContext $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function createSerializationContext(): SerializationContext
    {
        $callable = $this->callable;

        return $callable();
    }
}
