<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Visitor\Factory;

use FastPix\Sdk\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
interface SerializationVisitorFactory
{
    public function getVisitor(): SerializationVisitorInterface;
}
