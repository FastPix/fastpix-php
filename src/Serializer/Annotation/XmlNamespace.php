<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class XmlNamespace implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @Required
     * @var string|null
     */
    public $uri = null;

    /**
     * @var string
     */
    public $prefix = '';

    public function __construct(array $values = [], ?string $uri = null, string $prefix = '')
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'uri' => $uri, 'prefix' => $prefix]);
    }
}
