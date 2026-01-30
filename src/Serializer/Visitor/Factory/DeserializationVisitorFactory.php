<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Visitor\Factory;

use FastPix\Sdk\Serializer\Visitor\DeserializationVisitorInterface;

/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
interface DeserializationVisitorFactory
{
    public function getVisitor(): DeserializationVisitorInterface;
}
