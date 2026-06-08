<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class UnionDiscriminator implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /** @var array<string> */
    public $map = [];

    /** @var string */
    public $field = 'type';

    public function __construct(array $values = [], string $field = 'type', array $map = [])
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'field' => $field, 'map' => $map]);
    }
}
