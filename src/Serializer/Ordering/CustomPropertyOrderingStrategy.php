<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Ordering;

final class CustomPropertyOrderingStrategy implements PropertyOrderingInterface
{
    /** @var int[] property => weight */
    private $ordering;

    /**
     * @param int[] $ordering property => weight
     */
    public function __construct(array $ordering)
    {
        $this->ordering = $ordering;
    }

    /**
     * {@inheritdoc}
     */
    public function order(array $properties): array
    {
        $currentSorting = $properties ? array_combine(array_keys($properties), range(1, \count($properties))) : [];

        uksort($properties, function ($a, $b) use ($currentSorting) {
            $existsA = isset($this->ordering[$a]);
            $existsB = isset($this->ordering[$b]);

            if ($existsA && $existsB) {
                return $this->ordering[$a] < $this->ordering[$b] ? -1 : 1;
            }

            if (!$existsA && !$existsB) {
                return $currentSorting[$a] - $currentSorting[$b];
            }

            // Exactly one property has an explicit order: the ordered one comes first.
            return $existsA ? -1 : 1;
        });

        return $properties;
    }
}
