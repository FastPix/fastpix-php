<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata;

class VirtualPropertyMetadata extends PropertyMetadata
{
    public function __construct(string $class, string $methodName)
    {
        if (0 === strpos($methodName, 'get')) {
            $fieldName = lcfirst(substr($methodName, 3));
        } else {
            $fieldName = $methodName;
        }

        $this->class = $class;
        $this->name = $fieldName;
        $this->getter = $methodName;
        $this->readOnly = true;
    }

    /**
     * Virtual properties are read through the method captured as the getter in the
     * constructor, so they have no configurable accessor. This override intentionally does
     * nothing to suppress the accessor configuration applied by the parent class.
     */
    public function setAccessor(string $type, ?string $getter = null, ?string $setter = null): void
    {
        // Intentionally left empty: virtual properties resolve via their getter method.
    }
}
