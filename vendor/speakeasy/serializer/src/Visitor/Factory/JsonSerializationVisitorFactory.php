<?php

declare(strict_types=1);

namespace Speakeasy\Serializer\Visitor\Factory;

use Speakeasy\Serializer\JsonSerializationVisitor;
use Speakeasy\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class JsonSerializationVisitorFactory implements SerializationVisitorFactory
{
    /**
     * @var int
     */
    private $options = JSON_PRESERVE_ZERO_FRACTION;

    public function getVisitor(): SerializationVisitorInterface
    {
        return new JsonSerializationVisitor($this->options);
    }

    public function setOptions(int $options): self
    {
        $this->options = $options;

        return $this;
    }
}
