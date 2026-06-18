<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class XmlRoot implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @Required
     * @var string|null
     */
    public $name = null;

    /**
     * @var string|null
     */
    public $namespace = null;

    /**
     * @var string|null
     */
    public $prefix = null;

    public function __construct($values = [], ?string $name = null, ?string $namespace = null, ?string $prefix = null)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'name' => $name, 'namespace' => $namespace, 'prefix' => $prefix]);
    }
}
