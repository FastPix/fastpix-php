<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class MaxDepth implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @Required
     * @var int
     */
    public $depth;

    public function __construct($values = [], int $depth = 0)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'depth' => $depth]);
    }
}
