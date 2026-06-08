<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class XmlAttribute implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @var string|null
     */
    public $namespace = null;

    public function __construct($values = [], ?string $namespace = null)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'namespace' => $namespace]);
    }
}
