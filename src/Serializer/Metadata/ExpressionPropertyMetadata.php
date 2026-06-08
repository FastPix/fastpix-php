<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata;

use FastPix\Sdk\Serializer\Expression\Expression;

/**
 * @Annotation
 * @Target("METHOD")
 *
 * @author Asmir Mustafic <goetas@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class ExpressionPropertyMetadata extends PropertyMetadata
{
    /**
     * @var string|Expression
     */
    public $expression;

    /**
     * @param string|Expression $expression
     */
    public function __construct(string $class, string $fieldName, $expression)
    {
        $this->class = $class;
        $this->name = $fieldName;
        $this->expression = $expression;
        $this->readOnly = true;
    }

    /**
     * Expression properties are resolved through their expression, so they have no
     * getter/setter accessor. This override intentionally does nothing to suppress the
     * accessor configuration applied by the parent class.
     */
    public function setAccessor(string $type, ?string $getter = null, ?string $setter = null): void
    {
        // Intentionally left empty: expression properties have no accessor.
    }

    /**
     * {@inheritdoc}
     */
    protected function serializeToArray(): array
    {
        return [
            $this->expression,
            parent::serializeToArray(),
        ];
    }

    protected function unserializeFromArray(array $data): void
    {
        [
            $this->expression,
            $parentData,
        ] = $data;

        parent::unserializeFromArray($parentData);
    }
}
