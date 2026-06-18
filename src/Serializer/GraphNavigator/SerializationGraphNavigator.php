<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\GraphNavigator;

use FastPix\Sdk\Serializer\Accessor\AccessorStrategyInterface;
use FastPix\Sdk\Serializer\Context;
use FastPix\Sdk\Serializer\EventDispatcher\EventDispatcher;
use FastPix\Sdk\Serializer\EventDispatcher\EventDispatcherInterface;
use FastPix\Sdk\Serializer\EventDispatcher\ObjectEvent;
use FastPix\Sdk\Serializer\EventDispatcher\PreSerializeEvent;
use FastPix\Sdk\Serializer\Exception\CircularReferenceDetectedException;
use FastPix\Sdk\Serializer\Exception\ExcludedClassException;
use FastPix\Sdk\Serializer\Exception\ExpressionLanguageRequiredException;
use FastPix\Sdk\Serializer\Exception\InvalidArgumentException;
use FastPix\Sdk\Serializer\Exception\NotAcceptableException;
use FastPix\Sdk\Serializer\Exception\RuntimeException;
use FastPix\Sdk\Serializer\Exception\SkipHandlerException;
use FastPix\Sdk\Serializer\Exception\UninitializedPropertyException;
use FastPix\Sdk\Serializer\Exclusion\ExpressionLanguageExclusionStrategy;
use FastPix\Sdk\Serializer\Expression\ExpressionEvaluatorInterface;
use FastPix\Sdk\Serializer\Functions;
use FastPix\Sdk\Serializer\GraphNavigator;
use FastPix\Sdk\Serializer\GraphNavigatorInterface;
use FastPix\Sdk\Serializer\Handler\HandlerRegistryInterface;
use FastPix\Sdk\Serializer\Metadata\ClassMetadata;
use FastPix\Sdk\Serializer\NullAwareVisitorInterface;
use FastPix\Sdk\Serializer\SerializationContext;
use FastPix\Sdk\Serializer\Visitor\SerializationVisitorInterface;
use FastPix\Sdk\Serializer\VisitorInterface;
use Metadata\MetadataFactoryInterface;

use function assert;

/**
 * Handles traversal along the object graph.
 *
 * This class handles traversal along the graph, and calls different methods
 * on visitors, or custom handlers to process its nodes.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
final class SerializationGraphNavigator extends GraphNavigator
{
    /**
     * Primitive/collection type names handled directly by {@see visitLeaf()}.
     * Used as a lookup set (values are irrelevant).
     *
     * @var array<string, true>
     */
    private const LEAF_TYPES = [
        'NULL' => true,
        'string' => true,
        'int' => true,
        'integer' => true,
        'bool' => true,
        'boolean' => true,
        'double' => true,
        'float' => true,
        'iterable' => true,
        'array' => true,
        'list' => true,
        'resource' => true,
    ];

    /**
     * @var SerializationVisitorInterface
     */
    protected $visitor;

    /**
     * @var SerializationContext
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
     * @var AccessorStrategyInterface
     */
    private $accessor;

    /**
     * @var bool
     */
    private $shouldSerializeNull;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        HandlerRegistryInterface $handlerRegistry,
        AccessorStrategyInterface $accessor,
        ?EventDispatcherInterface $dispatcher = null,
        ?ExpressionEvaluatorInterface $expressionEvaluator = null
    ) {
        $this->dispatcher = $dispatcher ?: new EventDispatcher();
        $this->metadataFactory = $metadataFactory;
        $this->handlerRegistry = $handlerRegistry;
        $this->accessor = $accessor;

        if ($expressionEvaluator) {
            $this->expressionExclusionStrategy = new ExpressionLanguageExclusionStrategy($expressionEvaluator);
        }
    }

    public function initialize(VisitorInterface $visitor, Context $context): void
    {
        assert($context instanceof SerializationContext);

        parent::initialize($visitor, $context);

        $this->shouldSerializeNull = $context->shouldSerializeNull();
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
        $type = $this->resolveType($data, $type);

        if (isset(self::LEAF_TYPES[$type['name']])) {
            return $this->visitLeaf($data, $type);
        }

        if ('union' === $type['name']) {
            return $this->serializeUnion($data, $type);
        }

        return $this->serializeObject($data, $type);
    }

    /**
     * Infers/normalizes the type for the given data in serialization mode.
     *
     * @param mixed $data
     */
    private function resolveType($data, ?array $type): array
    {
        // If the type was not given, we infer the most specific type from the input data.
        if (null === $type) {
            $typeName = \gettype($data);
            if ('object' === $typeName) {
                $typeName = \get_class($data);
            }

            $type = ['name' => $typeName, 'params' => []];
        } elseif (null === $data) {
            // If the data is null, force the type to null regardless of the input to
            // guarantee correct handling of null values without internal auto-casting.
            $type = ['name' => 'NULL', 'params' => []];
        }

        // Sometimes data can convey null but is not of a null type.
        // Visitors can have the power to add this custom null evaluation
        if ($this->visitor instanceof NullAwareVisitorInterface && true === $this->visitor->isNull($data)) {
            $type = ['name' => 'NULL', 'params' => []];
        }

        return $type;
    }

    /**
     * Handles primitive and collection (leaf) types.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function visitLeaf($data, array $type)
    {
        return match ($type['name']) {
            'NULL' => $this->visitNullType($data, $type),
            'string' => $this->visitor->visitString((string) $data, $type),
            'int', 'integer' => $this->visitor->visitInteger((int) $data, $type),
            'bool', 'boolean' => $this->visitor->visitBoolean((bool) $data, $type),
            'double', 'float' => $this->visitor->visitDouble((float) $data, $type),
            'iterable' => $this->visitor->visitArray(Functions::iterableToArray($data), $type),
            'array', 'list' => $this->visitor->visitArray((array) $data, $type),
            'resource' => $this->throwUnsupportedResource(),
        };
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    private function visitNullType($data, array $type)
    {
        if (!$this->shouldSerializeNull && !$this->isRootNullAllowed()) {
            throw new NotAcceptableException();
        }

        return $this->visitor->visitNull($data, $type);
    }

    private function throwUnsupportedResource(): never
    {
        $msg = 'Resources are not supported in serialized data.';
        if (null !== $path = $this->context->getPath()) {
            $msg .= ' Path: ' . $path;
        }

        throw new RuntimeException($msg);
    }

    /**
     * Handles union types via a custom handler, falling back to null when none applies.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function serializeUnion($data, array $type)
    {
        $handler = $this->handlerRegistry->getHandler(GraphNavigatorInterface::DIRECTION_SERIALIZATION, $type['name'], $this->format);
        if (null !== $handler) {
            try {
                return \call_user_func($handler, $this->visitor, $data, $type, $this->context);
            } catch (SkipHandlerException $e) {
                // Skip handler, fallback to default behavior
            }
        }

        return null;
    }

    /**
     * Handles the default (object) case of {@see accept()}.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function serializeObject($data, array $type)
    {
        if (null !== $data) {
            if ($this->context->isVisiting($data)) {
                throw new CircularReferenceDetectedException();
            }

            $this->context->startVisiting($data);
        }

        $type = $this->resolvePolymorphicType($data, $type);

        // Dispatch pre-serialization event before handling data to allow listeners to change the type.
        $type = $this->dispatchPreSerialize($data, $type);

        // First, try whether a custom handler exists for the given type. This is done
        // before loading metadata because the type name might not be a class, but
        // could also simply be an artificial type.
        $handled = $this->invokeHandler($data, $type);
        if (null !== $handled) {
            return $handled['result'];
        }

        $metadata = $this->loadMetadata($data, $type);

        return $this->visitObject($metadata, $data, $type);
    }

    /**
     * Resolves the actual (polymorphic) type of the object when applicable.
     *
     * @param mixed $data
     */
    private function resolvePolymorphicType($data, array $type): array
    {
        // If we're serializing a polymorphic type, then we'll be interested in the
        // metadata for the actual type of the object, not the base class.
        if ((class_exists($type['name'], false) || interface_exists($type['name'], false))
            && is_subclass_of($data, $type['name'], false)
            && null === $this->handlerRegistry->getHandler(GraphNavigatorInterface::DIRECTION_SERIALIZATION, $type['name'], $this->format)) {
            return ['name' => \get_class($data), 'params' => $type['params'] ?? []];
        }

        return $type;
    }

    /**
     * @param mixed $data
     */
    private function dispatchPreSerialize($data, array $type): array
    {
        if ($this->dispatcher->hasListeners('serializer.pre_serialize', $type['name'], $this->format)) {
            $this->dispatcher->dispatch('serializer.pre_serialize', $type['name'], $this->format, $event = new PreSerializeEvent($this->context, $data, $type));
            $type = $event->getType();
        }

        return $type;
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
        $handler = $this->handlerRegistry->getHandler(GraphNavigatorInterface::DIRECTION_SERIALIZATION, $type['name'], $this->format);
        if (null === $handler) {
            return null;
        }

        try {
            $rs = \call_user_func($handler, $this->visitor, $data, $type, $this->context);
            $this->context->stopVisiting($data);

            return ['result' => $rs];
        } catch (SkipHandlerException $e) {
            // Skip handler, fallback to default behavior
            return null;
        } catch (NotAcceptableException $e) {
            $this->context->stopVisiting($data);

            throw $e;
        }
    }

    /**
     * Loads and validates metadata for the type, throwing when the class is
     * excluded or the data is not an object.
     *
     * @param mixed $data
     */
    private function loadMetadata($data, array $type): ClassMetadata
    {
        $metadata = $this->metadataFactory->getMetadataForClass($type['name']);
        \assert($metadata instanceof ClassMetadata);

        if ($metadata->usingExpression && null === $this->expressionExclusionStrategy) {
            throw new ExpressionLanguageRequiredException(sprintf('To use conditional exclude/expose in %s you must configure the expression language.', $metadata->name));
        }

        if ((null !== $this->exclusionStrategy && $this->exclusionStrategy->shouldSkipClass($metadata, $this->context))
            || (null !== $this->expressionExclusionStrategy && $this->expressionExclusionStrategy->shouldSkipClass($metadata, $this->context))) {
            $this->context->stopVisiting($data);

            throw new ExcludedClassException();
        }

        if (!is_object($data)) {
            throw new InvalidArgumentException('Value at ' . $this->context->getPath() . ' is expected to be an object of class ' . $type['name'] . ' but is of type ' . gettype($data));
        }

        return $metadata;
    }

    /**
     * @return mixed
     */
    private function visitObject(ClassMetadata $metadata, object $data, array $type)
    {
        $this->context->getMetadataStack()->pushClassMetadata($metadata);

        foreach ($metadata->preSerializeMethods as $method) {
            $method->invoke($data);
        }

        $this->visitor->startVisitingObject($metadata, $data, $type);
        foreach ($metadata->propertyMetadata as $propertyMetadata) {
            $this->serializeProperty($data, $propertyMetadata);
        }

        $this->afterVisitingObject($metadata, $data, $type);

        return $this->visitor->endVisitingObject($metadata, $data, $type);
    }

    private function serializeProperty(object $data, $propertyMetadata): void
    {
        if ((null !== $this->exclusionStrategy && $this->exclusionStrategy->shouldSkipProperty($propertyMetadata, $this->context))
            || (null !== $this->expressionExclusionStrategy && $this->expressionExclusionStrategy->shouldSkipProperty($propertyMetadata, $this->context))) {
            return;
        }

        try {
            $v = $this->accessor->getValue($data, $propertyMetadata, $this->context);
        } catch (UninitializedPropertyException $e) {
            return;
        }

        if (null === $v && (true !== $this->shouldSerializeNull || $propertyMetadata->skipWhenNull)) {
            return;
        }

        $this->context->getMetadataStack()->pushPropertyMetadata($propertyMetadata);
        $this->visitor->visitProperty($propertyMetadata, $v);
        $this->context->getMetadataStack()->popPropertyMetadata();
    }

    private function isRootNullAllowed(): bool
    {
        return $this->context->hasAttribute('allows_root_null') && $this->context->getAttribute('allows_root_null') && 0 === $this->context->getVisitingSet()->count();
    }

    private function afterVisitingObject(ClassMetadata $metadata, object $object, array $type): void
    {
        $this->context->stopVisiting($object);
        $this->context->getMetadataStack()->popClassMetadata();

        foreach ($metadata->postSerializeMethods as $method) {
            $method->invoke($object);
        }

        if ($this->dispatcher->hasListeners('serializer.post_serialize', $metadata->name, $this->format)) {
            $this->dispatcher->dispatch('serializer.post_serialize', $metadata->name, $this->format, new ObjectEvent($this->context, $object, $type));
        }
    }
}
