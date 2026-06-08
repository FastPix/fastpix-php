<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "CLASS", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
final class Exclude implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @var string|null
     */
    public $if;

    public function __construct(array $values = [], ?string $if = null)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'if' => $if]);
    }
}
