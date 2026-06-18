<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata\Driver;

use FastPix\Sdk\Serializer\Metadata\ClassMetadata as SerializerClassMetadata;
use FastPix\Sdk\Serializer\Metadata\PropertyMetadata;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class DefaultValuePropertyDriver implements DriverInterface
{
    /**
     * @var DriverInterface
     */
    protected $delegate;

    public function __construct(DriverInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @return SerializerClassMetadata|null
     */
    public function loadMetadataForClass(ReflectionClass $class): ?ClassMetadata
    {
        $classMetadata = $this->delegate->loadMetadataForClass($class);

        if (null === $classMetadata) {
            return null;
        }

        \assert($classMetadata instanceof SerializerClassMetadata);

        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            \assert($propertyMetadata instanceof PropertyMetadata);
            if (null === $propertyMetadata->hasDefault) {
                $this->resolveDefaultValue($propertyMetadata);
            }
        }

        return $classMetadata;
    }

    private function resolveDefaultValue(PropertyMetadata $propertyMetadata): void
    {
        try {
            $propertyReflection = $this->getPropertyReflection($propertyMetadata);
            $propertyMetadata->hasDefault = false;

            if ($propertyReflection->hasDefaultValue() && $propertyReflection->hasType()) {
                $propertyMetadata->hasDefault = true;
                $propertyMetadata->defaultValue = $propertyReflection->getDefaultValue();
            } elseif ($propertyReflection->isPromoted()) {
                // need to get the parameter in the constructor to check for default values
                $this->resolvePromotedDefaultValue($propertyMetadata);
            }
        } catch (ReflectionException $e) {
            // No reflection available for this property; leave its defaults untouched.
        }
    }

    private function resolvePromotedDefaultValue(PropertyMetadata $propertyMetadata): void
    {
        $classReflection = $this->getClassReflection($propertyMetadata);
        $params = $classReflection->getConstructor()->getParameters();

        foreach ($params as $parameter) {
            if ($parameter->getName() !== $propertyMetadata->name) {
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $propertyMetadata->hasDefault = true;
                $propertyMetadata->defaultValue = $parameter->getDefaultValue();
            }

            return;
        }
    }

    private function getPropertyReflection(PropertyMetadata $propertyMetadata): ReflectionProperty
    {
        return new ReflectionProperty($propertyMetadata->class, $propertyMetadata->name);
    }

    private function getClassReflection(PropertyMetadata $propertyMetadata): ReflectionClass
    {
        return new ReflectionClass($propertyMetadata->class);
    }
}
