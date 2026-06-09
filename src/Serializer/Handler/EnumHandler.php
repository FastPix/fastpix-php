<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Handler;

use FastPix\Sdk\Serializer\Exception\InvalidMetadataException;
use FastPix\Sdk\Serializer\Exception\RuntimeException;
use FastPix\Sdk\Serializer\GraphNavigatorInterface;
use FastPix\Sdk\Serializer\SerializationContext;
use FastPix\Sdk\Serializer\Visitor\DeserializationVisitorInterface;
use FastPix\Sdk\Serializer\Visitor\SerializationVisitorInterface;

final class EnumHandler implements SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];

        foreach (['json', 'xml'] as $format) {
            $methods[] = [
                'type' => 'enum',
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserializeEnum',
            ];
            $methods[] = [
                'type' => 'enum',
                'format' => $format,
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'method' => 'serializeEnum',
            ];
        }

        return $methods;
    }

    public function serializeEnum(
        SerializationVisitorInterface $visitor,
        \UnitEnum $enum,
        array $type,
        SerializationContext $context
    ) {
        if ((isset($type['params'][1]) && 'value' === $type['params'][1]) || (!isset($type['params'][1]) && $enum instanceof \BackedEnum)) {
            if (!$enum instanceof \BackedEnum) {
                throw new InvalidMetadataException(sprintf('The type "%s" is not a backed enum, thus you can not use "value" as serialization mode for its value.', get_class($enum)));
            }

            $valueType = isset($type['params'][2]) ? ['name' => $type['params'][2]] : null;

            return $context->getNavigator()->accept($enum->value, $valueType);
        } else {
            return $context->getNavigator()->accept($enum->name);
        }
    }

    /**
     * @param int|string|\SimpleXMLElement $data
     * @param array $type
     */
    public function deserializeEnum(DeserializationVisitorInterface $visitor, $data, array $type): ?\UnitEnum
    {
        $enumType = $this->resolveEnumType($type['params'][0]);
        $caseValue = (string) $data;
        $ref = new \ReflectionEnum($enumType);

        if ($this->isValueMode($type, $enumType)) {
            return $this->deserializeBackedEnum($ref, $enumType, $caseValue);
        }

        if (!$ref->hasCase($caseValue)) {
            throw new InvalidMetadataException(sprintf('The type "%s" does not have the case "%s"', $ref->getName(), $caseValue));
        }

        return $ref->getCase($caseValue)->getValue();
    }

    /**
     * @param array|string $enumType
     */
    private function resolveEnumType($enumType): string
    {
        if (isset($enumType['name'])) {
            return $enumType['name'];
        }

        trigger_deprecation('jms/serializer', '3.31', "Using enum<'Type'> or similar is deprecated, use enum<Type> instead.");

        return $enumType;
    }

    private function isValueMode(array $type, string $enumType): bool
    {
        if (isset($type['params'][1])) {
            return 'value' === $type['params'][1];
        }

        return is_a($enumType, \BackedEnum::class, true);
    }

    private function deserializeBackedEnum(\ReflectionEnum $ref, string $enumType, string $caseValue): \BackedEnum
    {
        if (!is_a($enumType, \BackedEnum::class, true)) {
            throw new InvalidMetadataException(sprintf('The type "%s" is not a backed enum, thus you can not use "value" as serialization mode for its value.', $enumType));
        }

        if ('int' === $ref->getBackingType()->getName()) {
            if (!is_numeric($caseValue)) {
                throw new RuntimeException(sprintf('"%s" is not a valid backing value for enum "%s"', $caseValue, $enumType));
            }

            return $enumType::from((int) $caseValue);
        }

        return $enumType::from($caseValue);
    }
}
