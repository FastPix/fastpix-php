<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Handler;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\PersistentCollection as MongoPersistentCollection;
use Doctrine\ODM\PHPCR\PersistentCollection as PhpcrPersistentCollection;
use Doctrine\ORM\PersistentCollection as OrmPersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use FastPix\Sdk\Serializer\DeserializationContext;
use FastPix\Sdk\Serializer\GraphNavigatorInterface;
use FastPix\Sdk\Serializer\Metadata\PropertyMetadata;
use FastPix\Sdk\Serializer\SerializationContext;
use FastPix\Sdk\Serializer\Visitor\DeserializationVisitorInterface;
use FastPix\Sdk\Serializer\Visitor\SerializationVisitorInterface;

final class ArrayCollectionHandler implements SubscribingHandlerInterface
{
    public const COLLECTION_TYPES = [
        'ArrayCollection',
        ArrayCollection::class,
        OrmPersistentCollection::class,
        MongoPersistentCollection::class,
        PhpcrPersistentCollection::class,
    ];

    /**
     * @var bool
     */
    private $initializeExcluded;

    /**
     * @var ManagerRegistry|null
     */
    private $managerRegistry;

    public function __construct(
        bool $initializeExcluded = true,
        ?ManagerRegistry $managerRegistry = null
    ) {
        $this->initializeExcluded = $initializeExcluded;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        $formats = ['json', 'xml'];

        foreach (self::COLLECTION_TYPES as $type) {
            foreach ($formats as $format) {
                $methods[] = [
                    'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                    'type' => $type,
                    'format' => $format,
                    'method' => 'serializeCollection',
                ];

                $methods[] = [
                    'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                    'type' => $type,
                    'format' => $format,
                    'method' => 'deserializeCollection',
                ];
            }
        }

        return $methods;
    }

    /**
     * @return array|\ArrayObject
     */
    public function serializeCollection(SerializationVisitorInterface $visitor, Collection $collection, array $type, SerializationContext $context)
    {
        // We change the base type, and pass through possible parameters.
        $type['name'] = 'array';

        $context->stopVisiting($collection);

        if (false === $this->initializeExcluded) {
            $exclusionStrategy = $context->getExclusionStrategy();
            if (null !== $exclusionStrategy && $exclusionStrategy->shouldSkipClass($context->getMetadataFactory()->getMetadataForClass(\get_class($collection)), $context)) {
                $context->startVisiting($collection);

                return $visitor->visitArray([], $type);
            }
        }

        $result = $visitor->visitArray($collection->toArray(), $type);

        $context->startVisiting($collection);

        return $result;
    }

    /**
     * @param mixed $data
     */
    public function deserializeCollection(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type,
        DeserializationContext $context
    ): Collection {
        // See above.
        $type['name'] = 'array';

        $elements = new ArrayCollection($visitor->visitArray($data, $type));

        if (null === $this->managerRegistry) {
            return $elements;
        }

        return $this->mergeIntoManagedCollection($elements, $visitor, $context) ?? $elements;
    }

    /**
     * Merges the deserialized elements into the managed Doctrine collection of the
     * property currently being visited, returning it; or null when no managed
     * collection applies (so the caller falls back to the plain elements).
     */
    private function mergeIntoManagedCollection(ArrayCollection $elements, DeserializationVisitorInterface $visitor, DeserializationContext $context): ?Collection
    {
        $propertyMetadata = $context->getMetadataStack()->top();
        $objectManager = $propertyMetadata instanceof PropertyMetadata
            ? $this->managerRegistry->getManagerForClass($propertyMetadata->class)
            : null;
        if (null === $objectManager) {
            return null;
        }

        $classMetadata = $objectManager->getClassMetadata($propertyMetadata->class);
        $existingCollection = $this->isManagedCollectionAssociation($classMetadata, $propertyMetadata)
            ? $classMetadata->getFieldValue($visitor->getCurrentObject(), $propertyMetadata->name)
            : null;
        if (!$existingCollection instanceof OrmPersistentCollection) {
            return null;
        }

        $this->syncCollections($elements, $existingCollection);

        return $existingCollection;
    }

    /**
     * @param mixed $classMetadata Doctrine class metadata for the property's class
     */
    private function isManagedCollectionAssociation($classMetadata, PropertyMetadata $propertyMetadata): bool
    {
        return array_key_exists('name', $propertyMetadata->type)
            && in_array($propertyMetadata->type['name'], self::COLLECTION_TYPES)
            && $classMetadata->isCollectionValuedAssociation($propertyMetadata->name);
    }

    /**
     * Adds new elements to, and prunes removed elements from, the managed collection.
     */
    private function syncCollections(Collection $elements, OrmPersistentCollection $existingCollection): void
    {
        foreach ($elements as $element) {
            if (!$existingCollection->contains($element)) {
                $existingCollection->add($element);
            }
        }

        foreach ($existingCollection as $collectionElement) {
            if (!$elements->contains($collectionElement)) {
                $existingCollection->removeElement($collectionElement);
            }
        }
    }
}
