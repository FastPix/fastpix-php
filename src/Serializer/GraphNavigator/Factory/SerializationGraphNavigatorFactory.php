<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\GraphNavigator\Factory;

use FastPix\Sdk\Serializer\Accessor\AccessorStrategyInterface;
use FastPix\Sdk\Serializer\Accessor\DefaultAccessorStrategy;
use FastPix\Sdk\Serializer\EventDispatcher\EventDispatcher;
use FastPix\Sdk\Serializer\EventDispatcher\EventDispatcherInterface;
use FastPix\Sdk\Serializer\Expression\ExpressionEvaluatorInterface;
use FastPix\Sdk\Serializer\GraphNavigator\SerializationGraphNavigator;
use FastPix\Sdk\Serializer\GraphNavigatorInterface;
use FastPix\Sdk\Serializer\Handler\HandlerRegistryInterface;
use Metadata\MetadataFactoryInterface;

final class SerializationGraphNavigatorFactory implements GraphNavigatorFactoryInterface
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
        ?AccessorStrategyInterface $accessor = null,
        ?EventDispatcherInterface $dispatcher = null,
        ?ExpressionEvaluatorInterface $expressionEvaluator = null
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->handlerRegistry = $handlerRegistry;
        $this->accessor = $accessor ?: new DefaultAccessorStrategy();
        $this->dispatcher = $dispatcher ?: new EventDispatcher();
        $this->expressionEvaluator = $expressionEvaluator;
    }

    public function getGraphNavigator(): GraphNavigatorInterface
    {
        return new SerializationGraphNavigator($this->metadataFactory, $this->handlerRegistry, $this->accessor, $this->dispatcher, $this->expressionEvaluator);
    }
}
