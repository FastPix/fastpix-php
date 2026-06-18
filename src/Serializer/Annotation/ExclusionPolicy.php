<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

use FastPix\Sdk\Serializer\Exception\RuntimeException;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class ExclusionPolicy implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    public const NONE = 'NONE';
    public const ALL = 'ALL';

    /**
     * @var string|null
     */
    public $policy = 'NONE';

    public function __construct($values = [], ?string $policy = null)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'policy' => $policy]);

        $this->policy = strtoupper($this->policy);

        if (self::NONE !== $this->policy && self::ALL !== $this->policy) {
            throw new RuntimeException('Exclusion policy must either be "ALL", or "NONE".');
        }
    }
}
