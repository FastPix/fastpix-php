<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Accessor implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @var string|null
     */
    public $getter = null;

    /**
     * @var string|null
     */
    public $setter = null;

    public function __construct(array $values = [], ?string $getter = null, ?string $setter = null)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'getter' => $getter, 'setter' => $setter]);
    }
}
