<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

abstract class Version implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /**
     * @Required
     * @var string|null
     */
    public $version = null;

    // NOSONAR php:S1172 - constructor parameters are consumed via get_defined_vars() to map attribute arguments to properties.
    public function __construct($values = [], ?string $version = null)
    {
        $this->loadAnnotationParameters(get_defined_vars());
    }
}
