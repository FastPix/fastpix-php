<?php

declare(strict_types=1);

namespace Speakeasy\Serializer\Exclusion;

use Speakeasy\Serializer\Context;
use Speakeasy\Serializer\Metadata\ClassMetadata;
use Speakeasy\Serializer\Metadata\PropertyMetadata;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
final class DepthExclusionStrategy implements ExclusionStrategyInterface
{
    public function shouldSkipClass(ClassMetadata $metadata, Context $context): bool
    {
        return $this->isTooDeep($context);
    }

    public function shouldSkipProperty(PropertyMetadata $property, Context $context): bool
    {
        return $this->isTooDeep($context);
    }

    private function isTooDeep(Context $context): bool
    {
        $relativeDepth = 0;

        foreach ($context->getMetadataStack() as $metadata) {
            if (!$metadata instanceof PropertyMetadata) {
                continue;
            }

            $relativeDepth++;

            if (0 === $metadata->maxDepth && $context->getMetadataStack()->top() === $metadata) {
                continue;
            }

            if (null !== $metadata->maxDepth && $relativeDepth > $metadata->maxDepth) {
                return true;
            }
        }

        return false;
    }
}
