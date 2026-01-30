<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Naming;

use FastPix\Sdk\Serializer\Metadata\PropertyMetadata;

final class IdenticalPropertyNamingStrategy implements PropertyNamingStrategyInterface
{
    public function translateName(PropertyMetadata $property): string
    {
        return $property->name;
    }
}
