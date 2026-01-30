<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Builder;

use Metadata\Driver\DriverInterface;

interface DriverFactoryInterface
{
    /**
     * @param array<string, string> $metadataDirs
     * @param object|null $annotationReader Doctrine\Common\Annotations\Reader when doctrine/annotations is installed (optional; PHP 8+ uses attributes)
     */
    public function createDriver(array $metadataDirs, ?object $annotationReader = null): DriverInterface;
}
