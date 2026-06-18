<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata;

class StaticPropertyMetadata extends PropertyMetadata
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * StaticPropertyMetadata constructor.
     *
     * @param mixed $fieldValue
     * @param array $groups
     */
    public function __construct(string $className, string $fieldName, $fieldValue, array $groups = [])
    {
        $this->class = $className;
        $this->name = $fieldName;
        $this->serializedName = $fieldName;
        $this->value = $fieldValue;
        $this->readOnly = true;
        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Static properties expose a fixed value rather than a class member, so they have no
     * getter/setter accessor. This override intentionally does nothing to suppress the
     * accessor configuration applied by the parent class.
     */
    public function setAccessor(string $type, ?string $getter = null, ?string $setter = null): void
    {
        // Intentionally left empty: static properties have no accessor.
    }

    protected function serializeToArray(): array
    {
        return [
            $this->value,
            parent::serializeToArray(),
        ];
    }

    protected function unserializeFromArray(array $data): void
    {
        [
            $this->value,
            $parentData,
        ] = $data;

        parent::unserializeFromArray($parentData);
    }
}
