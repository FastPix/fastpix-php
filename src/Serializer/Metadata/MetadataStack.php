<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata;

use FastPix\Sdk\Serializer\Exception\RuntimeException;

/**
 * Stack of the class and property metadata currently being visited.
 *
 * Extends \SplStack so existing consumers can keep iterating it or calling top()
 * directly while the push/pop bookkeeping lives here instead of on the Context.
 *
 * @internal
 *
 * @extends \SplStack<ClassMetadata|PropertyMetadata>
 */
final class MetadataStack extends \SplStack
{
    public function pushClassMetadata(ClassMetadata $metadata): void
    {
        $this->push($metadata);
    }

    public function pushPropertyMetadata(PropertyMetadata $metadata): void
    {
        $this->push($metadata);
    }

    public function popPropertyMetadata(): void
    {
        $metadata = $this->pop();

        if (!$metadata instanceof PropertyMetadata) {
            throw new RuntimeException('Context metadataStack not working well');
        }
    }

    public function popClassMetadata(): void
    {
        $metadata = $this->pop();

        if (!$metadata instanceof ClassMetadata) {
            throw new RuntimeException('Context metadataStack not working well');
        }
    }

    /**
     * Returns the property names from the bottom of the stack to the top.
     *
     * @return string[]
     */
    public function getCurrentPath(): array
    {
        $paths = [];
        foreach ($this as $metadata) {
            if ($metadata instanceof PropertyMetadata) {
                array_unshift($paths, $metadata->name);
            }
        }

        return $paths;
    }
}
