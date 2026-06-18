<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"CLASS","PROPERTY"})
 *
 * @final
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY)]
/* final */ class ReadOnlyProperty implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @var bool
     */
    public $readOnly = true;

    public function __construct(array $values = [], bool $readOnly = true)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'readOnly' => $readOnly]);
    }
}
