<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class XmlMap extends XmlCollection
{
    use AnnotationUtilsTrait;

    /**
     * @var string
     */
    public $keyAttribute = '_key';

    public function __construct(array $values = [], string $keyAttribute = '_key', string $entry = 'entry', bool $inline = false, ?string $namespace = null, bool $skipWhenEmpty = true)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'keyAttribute' => $keyAttribute, 'entry' => $entry, 'inline' => $inline, 'namespace' => $namespace, 'skipWhenEmpty' => $skipWhenEmpty]);
    }
}
