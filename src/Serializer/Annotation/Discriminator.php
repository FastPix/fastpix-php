<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Discriminator implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /** @var array<string> */
    public $map = [];

    /** @var string */
    public $field = 'type';

    /** @var bool */
    public $disabled = false;

    /** @var string[] */
    public $groups = [];

    public function __construct(array $values = [], string $field = 'type', array $groups = [], array $map = [], bool $disabled = false)
    {
        // Pass parameters explicitly (instead of get_defined_vars()) so they are
        // referenced directly; order is preserved to match loadAnnotationParameters().
        $this->loadAnnotationParameters(['values' => $values, 'field' => $field, 'groups' => $groups, 'map' => $map, 'disabled' => $disabled]);
    }
}
