<?php

declare(strict_types=1);

namespace Speakeasy\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class XmlAttributeMap implements SerializerAttribute
{
}
