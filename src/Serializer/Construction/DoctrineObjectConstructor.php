<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Construction;

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use FastPix\Sdk\Serializer\DeserializationContext;
use FastPix\Sdk\Serializer\Exception\InvalidArgumentException;
use FastPix\Sdk\Serializer\Exception\ObjectConstructionException;
use FastPix\Sdk\Serializer\Exclusion\ExpressionLanguageExclusionStrategy;
use FastPix\Sdk\Serializer\Metadata\ClassMetadata;
use FastPix\Sdk\Serializer\Metadata\PropertyMetadata;
use FastPix\Sdk\Serializer\Visitor\DeserializationVisitorInterface;

use function is_array;

/**
 * Doctrine object constructor for new (or existing) objects during deserialization.
 */
final class DoctrineObjectConstructor implements ObjectConstructorInterface
{
    public const ON_MISSING_NULL = 'null';
    public const ON_MISSING_EXCEPTION = 'exception';
    public const ON_MISSING_FALLBACK = 'fallback';
    /**
     * @var string
     */
    private $fallbackStrategy;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var ObjectConstructorInterface
     */
    private $fallbackConstructor;

    /**
     * @var ExpressionLanguageExclusionStrategy|null
     */
    private $expressionLanguageExclusionStrategy;

    /**
     * @param ManagerRegistry $managerRegistry     Manager registry
     * @param ObjectConstructorInterface $fallbackConstructor Fallback object constructor
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ObjectConstructorInterface $fallbackConstructor,
        string $fallbackStrategy = self::ON_MISSING_NULL,
        ?ExpressionLanguageExclusionStrategy $expressionLanguageExclusionStrategy = null
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->fallbackConstructor = $fallbackConstructor;
        $this->fallbackStrategy = $fallbackStrategy;
        $this->expressionLanguageExclusionStrategy = $expressionLanguageExclusionStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function construct(DeserializationVisitorInterface $visitor, ClassMetadata $metadata, $data, array $type, DeserializationContext $context): ?object
    {
        $objectManager = $this->managerRegistry->getManagerForClass($metadata->name);

        // No ObjectManager or no ClassMetadata found, proceed with normal deserialization
        if (!$objectManager || $objectManager->getMetadataFactory()->isTransient($metadata->name)) {
            return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Managed entity, check for proxy load
        if (!is_array($data) && !(is_object($data) && 'SimpleXMLElement' === get_class($data))) {
            \assert($objectManager instanceof EntityManagerInterface || $objectManager instanceof DocumentManagerInterface);

            // Single identifier, load proxy
            return $objectManager->getReference($metadata->name, $data);
        }

        return $this->constructManagedEntity($objectManager, $visitor, $metadata, $data, $type, $context);
    }

    /**
     * Loads (or updates) a managed entity addressed by its full identifier set,
     * falling back to the default constructor when identifiers are missing.
     */
    private function constructManagedEntity(ObjectManager $objectManager, DeserializationVisitorInterface $visitor, ClassMetadata $metadata, $data, array $type, DeserializationContext $context): ?object
    {
        $identifierList = $this->resolveIdentifierList($objectManager, $metadata, $data, $context);

        // Missing/excluded identifier(s); also guards the embeddable-class edge case
        // where isTransient() misreports (https://github.com/doctrine/persistence/issues/37).
        if (empty($identifierList)) {
            return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Entity update, load it from database
        $object = $objectManager->find($metadata->name, $identifierList);

        if (null === $object) {
            return $this->handleMissingObject($visitor, $metadata, $data, $type, $context);
        }

        $objectManager->initializeObject($object);

        return $object;
    }

    /**
     * Builds the identifier map for the entity, or returns null when any
     * identifier property is absent, excluded, or missing from $data.
     *
     * @return array<string, mixed>|null
     */
    private function resolveIdentifierList(ObjectManager $objectManager, ClassMetadata $metadata, $data, DeserializationContext $context): ?array
    {
        $classMetadata = $objectManager->getClassMetadata($metadata->name);
        $identifierList = [];

        foreach ($classMetadata->getIdentifierFieldNames() as $name) {
            $propertyMetadata = $metadata->propertyMetadata[$name] ?? null;

            // Avoid calling objectManager->find if an identifier property is absent,
            // excluded by an exclusion strategy, or not present in the incoming data.
            if (null === $propertyMetadata
                || $this->isIdentifierFieldExcluded($propertyMetadata, $context)
                || !$this->dataHasIdentifier($data, $propertyMetadata->serializedName)) {
                return null;
            }

            $identifierList[$name] = $this->extractIdentifier($data, $propertyMetadata->serializedName);
        }

        return $identifierList;
    }

    /**
     * @param array<mixed>|object $data
     */
    private function dataHasIdentifier($data, string $serializedName): bool
    {
        if (is_array($data)) {
            return array_key_exists($serializedName, $data);
        }

        return is_object($data) && property_exists($data, $serializedName);
    }

    /**
     * @param array<mixed>|object $data
     *
     * @return mixed
     */
    private function extractIdentifier($data, string $serializedName)
    {
        if (is_object($data) && 'SimpleXMLElement' === get_class($data)) {
            return (string) $data->{$serializedName};
        }

        return $data[$serializedName];
    }

    private function handleMissingObject(DeserializationVisitorInterface $visitor, ClassMetadata $metadata, $data, array $type, DeserializationContext $context): ?object
    {
        switch ($this->fallbackStrategy) {
            case self::ON_MISSING_NULL:
                return null;

            case self::ON_MISSING_EXCEPTION:
                throw new ObjectConstructionException(sprintf('Entity %s can not be found', $metadata->name));

            case self::ON_MISSING_FALLBACK:
                return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);

            default:
                throw new InvalidArgumentException('The provided fallback strategy for the object constructor is not valid');
        }
    }

    private function isIdentifierFieldExcluded(PropertyMetadata $propertyMetadata, DeserializationContext $context): bool
    {
        $exclusionStrategy = $context->getExclusionStrategy();
        if (null !== $exclusionStrategy && $exclusionStrategy->shouldSkipProperty($propertyMetadata, $context)) {
            return true;
        }

        return null !== $this->expressionLanguageExclusionStrategy && $this->expressionLanguageExclusionStrategy->shouldSkipProperty($propertyMetadata, $context);
    }
}
