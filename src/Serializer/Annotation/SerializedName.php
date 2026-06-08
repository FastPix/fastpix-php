<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class SerializedName implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @var string|null
     */
    public $name = null;

    public function __construct($values = [], ?string $name = null)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'name' => $name]);
    }
}
