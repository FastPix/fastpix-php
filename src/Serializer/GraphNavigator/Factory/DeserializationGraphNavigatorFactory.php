<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\GraphNavigator\Factory;

use FastPix\Sdk\Serializer\Accessor\AccessorStrategyInterface;
use FastPix\Sdk\Serializer\Construction\ObjectConstructorInterface;
use FastPix\Sdk\Serializer\EventDispatcher\EventDispatcherInterface;
use FastPix\Sdk\Serializer\Expression\ExpressionEvaluatorInterface;
use FastPix\Sdk\Serializer\GraphNavigator\DeserializationGraphNavigator;
use FastPix\Sdk\Serializer\GraphNavigatorInterface;
use FastPix\Sdk\Serializer\Handler\HandlerRegistryInterface;
use Metadata\MetadataFactoryInterface;

final class DeserializationGraphNavigatorFactory implements GraphNavigatorFactoryInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;
    /**
     * @var HandlerRegistryInterface
     */
    private $handlerRegistry;
    /**
     * @var ObjectConstructorInterface
     */
    private $objectConstructor;
    /**
     * @var AccessorStrategyInterface
     */
    private $accessor;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var ExpressionEvaluatorInterface
     */
    private $expressionEvaluator;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        HandlerRegistryInterface $handlerRegistry,
        ObjectConstructorInterface $objectConstructor,
        AccessorStrategyInterface $accessor,
        ?EventDispatcherInterface $dispatcher = null,
        ?ExpressionEvaluatorInterface $expressionEvaluator = null
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->handlerRegistry = $handlerRegistry;
        $this->objectConstructor = $objectConstructor;
        $this->accessor = $accessor;
        $this->dispatcher = $dispatcher;
        $this->expressionEvaluator = $expressionEvaluator;
    }

    public function getGraphNavigator(): GraphNavigatorInterface
    {
        return new DeserializationGraphNavigator($this->metadataFactory, $this->handlerRegistry, $this->objectConstructor, $this->accessor, $this->dispatcher, $this->expressionEvaluator);
    }
}
