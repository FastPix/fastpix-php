<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\GraphNavigator;

use FastPix\Sdk\Serializer\Accessor\AccessorStrategyInterface;
use FastPix\Sdk\Serializer\Construction\ObjectConstructorInterface;
use FastPix\Sdk\Serializer\DeserializationContext;
use FastPix\Sdk\Serializer\EventDispatcher\EventDispatcher;
use FastPix\Sdk\Serializer\EventDispatcher\EventDispatcherInterface;
use FastPix\Sdk\Serializer\EventDispatcher\ObjectEvent;
use FastPix\Sdk\Serializer\EventDispatcher\PreDeserializeEvent;
use FastPix\Sdk\Serializer\Exception\ExpressionLanguageRequiredException;
use FastPix\Sdk\Serializer\Exception\LogicException;
use FastPix\Sdk\Serializer\Exception\NotAcceptableException;
use FastPix\Sdk\Serializer\Exception\PropertyMissingException;
use FastPix\Sdk\Serializer\Exception\RuntimeException;
use FastPix\Sdk\Serializer\Exception\SkipHandlerException;
use FastPix\Sdk\Serializer\Exclusion\ExpressionLanguageExclusionStrategy;
use FastPix\Sdk\Serializer\Expression\ExpressionEvaluatorInterface;
use FastPix\Sdk\Serializer\GraphNavigator;
use FastPix\Sdk\Serializer\GraphNavigatorInterface;
use FastPix\Sdk\Serializer\Handler\HandlerRegistryInterface;
use FastPix\Sdk\Serializer\Metadata\ClassMetadata;
use FastPix\Sdk\Serializer\NullAwareVisitorInterface;
use FastPix\Sdk\Serializer\Visitor\DeserializationVisitorInterface;
use Metadata\MetadataFactoryInterface;

/**
 * Handles traversal along the object graph.
 *
 * This class handles traversal along the graph, and calls different methods
 * on visitors, or custom handlers to process its nodes.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
final class DeserializationGraphNavigator extends GraphNavigator implements GraphNavigatorInterface
{
    /**
     * Maps a primitive/collection type name to the visitor method handling it.
     *
     * @var array<string, string>
     */
    private const SCALAR_VISITORS = [
        'NULL' => 'visitNull',
        'string' => 'visitString',
        'int' => 'visitInteger',
        'integer' => 'visitInteger',
        'bool' => 'visitBoolean',
        'boolean' => 'visitBoolean',
        'double' => 'visitDouble',
        'float' => 'visitDouble',
        'array' => 'visitArray',
        'iterable' => 'visitArray',
        'list' => 'visitArray',
    ];

    /**
     * @var DeserializationVisitorInterface
     */
    protected $visitor;

    /**
     * @var DeserializationContext
     */
    protected $context;

    /**
     * @var ExpressionLanguageExclusionStrategy
     */
    private $expressionExclusionStrategy;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

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

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        HandlerRegistryInterface $handlerRegistry,
        ObjectConstructorInterface $objectConstructor,
        AccessorStrategyInterface $accessor,
        ?EventDispatcherInterface $dispatcher = null,
        ?ExpressionEvaluatorInterface $expressionEvaluator = null
    ) {
        $this->dispatcher = $dispatcher ?: new EventDispatcher();
        $this->metadataFactory = $metadataFactory;
        $this->handlerRegistry = $handlerRegistry;
        $this->objectConstructor = $objectConstructor;
        $this->accessor = $accessor;
        if ($expressionEvaluator) {
            $this->expressionExclusionStrategy = new ExpressionLanguageExclusionStrategy($expressionEvaluator);
        }
    }

    /**
     * Called for each node of the graph that is being traversed.
     *
     * @param mixed $data the data depends on the direction, and type of visitor
     * @param array|null $type array has the format ["name" => string, "params" => array]
     *
     * @return mixed the return value depends on the direction, and type of visitor
     */
    public function accept($data, ?array $type = null)
    {
        // The type must be given for all properties when deserializing.
        if (null === $type) {
            throw new RuntimeException('The type must be given for all properties when deserializing.');
        }

        // Sometimes data can convey null but is not of a null type.
        // Visitors can have the power to add this custom null evaluation
        if ($this->visitor instanceof NullAwareVisitorInterface && true === $this->visitor->isNull($data)) {
            $type = ['name' => 'NULL', 'params' => []];
        }

        $scalarVisitor = self::SCALAR_VISITORS[$type['name']] ?? null;
        if (null !== $scalarVisitor) {
            return $this->visitor->{$scalarVisitor}($data, $type);
        }

        if ('resource' === $type['name']) {
            throw new RuntimeException('Resources are not supported in serialized data.');
        }

        return $this->deserializeObject($data, $type);
    }

    /**
     * Handles the default (object) case of {@see accept()}.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function deserializeObject($data, array $type)
    {
        $this->context->increaseDepth();

        // Dispatch pre-deserialization event before handling data to allow listeners to change the type.
        [$data, $type] = $this->dispatchPreDeserialize($data, $type);

        // First, try whether a custom handler exists for the given type. This is done
        // before loading metadata because the type name might not be a class, but
        // could also simply be an artificial type.
        $handled = $this->invokeHandler($data, $type);
        if (null !== $handled) {
            $this->context->decreaseDepth();

            return $handled['result'];
        }

        $metadata = $this->loadMetadata($data, $type);

        if (null !== $this->exclusionStrategy && $this->exclusionStrategy->shouldSkipClass($metadata, $this->context)) {
            $this->context->decreaseDepth();

            return null;
        }

        return $this->constructAndVisit($metadata, $data, $type);
    }

    /**
     * @param mixed $data
     *
     * @return array{0: mixed, 1: array} the (possibly listener-modified) data and type
     */
    private function dispatchPreDeserialize($data, array $type): array
    {
        if ($this->dispatcher->hasListeners('serializer.pre_deserialize', $type['name'], $this->format)) {
            $this->dispatcher->dispatch('serializer.pre_deserialize', $type['name'], $this->format, $event = new PreDeserializeEvent($this->context, $data, $type));
            $type = $event->getType();
            $data = $event->getData();
        }

        return [$data, $type];
    }

    /**
     * Runs a custom handler for the type, if one is registered.
     *
     * @param mixed $data
     *
     * @return array{result: mixed}|null the wrapped handler result, or null when no
     *                                   handler applies (none registered, or it skipped)
     */
    private function invokeHandler($data, array $type): ?array
    {
        $handler = $this->handlerRegistry->getHandler(GraphNavigatorInterface::DIRECTION_DESERIALIZATION, $type['name'], $this->format);
        if (null === $handler) {
            return null;
        }

        try {
            return ['result' => \call_user_func($handler, $this->visitor, $data, $type, $this->context)];
        } catch (SkipHandlerException $e) {
            // Skip handler, fallback to default behavior
            return null;
        }
    }

    /**
     * @param mixed $data
     */
    private function loadMetadata($data, array $type): ClassMetadata
    {
        $metadata = $this->metadataFactory->getMetadataForClass($type['name']);
        \assert($metadata instanceof ClassMetadata);

        if ($metadata->usingExpression && !$this->expressionExclusionStrategy) {
            throw new ExpressionLanguageRequiredException(sprintf('To use conditional exclude/expose in %s you must configure the expression language.', $metadata->name));
        }

        if (!empty($metadata->discriminatorMap) && $type['name'] === $metadata->discriminatorBaseClass) {
            $metadata = $this->resolveMetadata($data, $metadata);
            \assert($metadata instanceof ClassMetadata);
        }

        return $metadata;
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    private function constructAndVisit(ClassMetadata $metadata, $data, array $type)
    {
        $this->context->getMetadataStack()->pushClassMetadata($metadata);

        $object = $this->objectConstructor->construct($this->visitor, $metadata, $data, $type, $this->context);

        if (null === $object) {
            $this->context->getMetadataStack()->popClassMetadata();
            $this->context->decreaseDepth();

            return $this->visitor->visitNull($data, $type);
        }

        $this->visitor->startVisitingObject($metadata, $object, $type);
        foreach ($metadata->propertyMetadata as $propertyMetadata) {
            $this->deserializeProperty($object, $propertyMetadata, $data);
        }

        $rs = $this->visitor->endVisitingObject($metadata, $data, $type);
        $this->afterVisitingObject($metadata, $rs, $type);

        return $rs;
    }

    /**
     * @param mixed $data
     */
    private function deserializeProperty(object $object, $propertyMetadata, $data): void
    {
        if (null !== $this->exclusionStrategy && $this->exclusionStrategy->shouldSkipProperty($propertyMetadata, $this->context)) {
            return;
        }

        if (null !== $this->expressionExclusionStrategy && $this->expressionExclusionStrategy->shouldSkipProperty($propertyMetadata, $this->context)) {
            return;
        }

        if ($propertyMetadata->readOnly) {
            return;
        }

        $allowsNull = null === $propertyMetadata->type ? true : $this->allowsNull($propertyMetadata->type);

        $this->context->getMetadataStack()->pushPropertyMetadata($propertyMetadata);
        try {
            $v = $this->visitor->visitProperty($propertyMetadata, $data);
            $this->accessor->setValue($object, $v, $propertyMetadata, $this->context);
        } catch (NotAcceptableException $e) {
            $this->applyPropertyDefault($object, $propertyMetadata, $allowsNull);
        }

        $this->context->getMetadataStack()->popPropertyMetadata();
    }

    private function applyPropertyDefault(object $object, $propertyMetadata, bool $allowsNull): void
    {
        if (true === $propertyMetadata->hasDefault) {
            $cloned = clone $propertyMetadata;
            $cloned->setter = null;
            $this->accessor->setValue($object, $cloned->defaultValue, $cloned, $this->context);
        } elseif (!$allowsNull && $this->context->getRequireAllRequiredProperties()) {
            throw new PropertyMissingException('Property ' . $propertyMetadata->name . ' is missing from data');
        }
    }

    /**
     * @param mixed $data
     */
    private function resolveMetadata($data, ClassMetadata $metadata): ?ClassMetadata
    {
        $typeValue = $this->visitor->visitDiscriminatorMapProperty($data, $metadata);

        if (!isset($metadata->discriminatorMap[$typeValue])) {
            throw new LogicException(sprintf(
                'The type value "%s" does not exist in the discriminator map of class "%s". Available types: %s',
                $typeValue,
                $metadata->name,
                implode(', ', array_keys($metadata->discriminatorMap)),
            ));
        }

        return $this->metadataFactory->getMetadataForClass($metadata->discriminatorMap[$typeValue]);
    }

    private function afterVisitingObject(ClassMetadata $metadata, object $object, array $type): void
    {
        $this->context->decreaseDepth();
        $this->context->getMetadataStack()->popClassMetadata();

        foreach ($metadata->postDeserializeMethods as $method) {
            $method->invoke($object);
        }

        if ($this->dispatcher->hasListeners('serializer.post_deserialize', $metadata->name, $this->format)) {
            $this->dispatcher->dispatch('serializer.post_deserialize', $metadata->name, $this->format, new ObjectEvent($this->context, $object, $type));
        }
    }

    private function allowsNull(array $type): bool
    {
        $allowsNull = false;
        if ('union' === $type['name'] && isset($type['params'][0])) {
            foreach ($type['params'] as $param) {
                if ('NULL' === $param['name']) {
                    $allowsNull = true;
                }
            }
        } elseif ('NULL' === $type['name']) {
            $allowsNull = true;
        }

        return $allowsNull;
    }
}
