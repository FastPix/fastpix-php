<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

use FastPix\Sdk\Serializer\Exception\InvalidArgumentException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class Type implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @Required
     * @var string|\Stringable|null
     */
    public $name = null;

    // NOSONAR php:S1172 - constructor parameters are consumed via get_defined_vars() to map attribute arguments to properties.
    public function __construct($values = [], $name = null)
    {
        if ((null !== $name) && !is_string($name) && !(is_object($name) && method_exists($name, '__toString'))) {
            throw new InvalidArgumentException(
                'Type must be either string, null or object implements __toString() method.',
            );
        }

        if (is_object($name)) {
            $name = (string) $name;
        }

        if (is_object($values)) {
            if (false === method_exists($values, '__toString')) {
                throw new InvalidArgumentException(
                    'Type must be either string or object implements __toString() method.',
                );
            }

            $values = (string) $values;
        }

        $this->loadAnnotationParameters(get_defined_vars());
    }
}
