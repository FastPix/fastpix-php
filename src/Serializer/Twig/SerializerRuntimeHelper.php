<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Twig;

use FastPix\Sdk\Serializer\SerializationContext;
use FastPix\Sdk\Serializer\SerializerInterface;

/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class SerializerRuntimeHelper
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $object
     */
    public function serialize($object, string $type = 'json', ?SerializationContext $context = null): string
    {
        return $this->serializer->serialize($object, $type, $context);
    }
}
