<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class XmlValue implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @var bool
     */
    public $cdata = true;

    public function __construct(array $values = [], bool $cdata = true)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'cdata' => $cdata]);
    }
}
