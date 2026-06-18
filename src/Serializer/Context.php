<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer;

use FastPix\Sdk\Serializer\Exception\LogicException;
use FastPix\Sdk\Serializer\Exclusion\DepthExclusionStrategy;
use FastPix\Sdk\Serializer\Exclusion\DisjunctExclusionStrategy;
use FastPix\Sdk\Serializer\Exclusion\ExclusionStrategyInterface;
use FastPix\Sdk\Serializer\Exclusion\GroupsExclusionStrategy;
use FastPix\Sdk\Serializer\Exclusion\VersionExclusionStrategy;
use FastPix\Sdk\Serializer\Metadata\MetadataStack;
use Metadata\MetadataFactoryInterface;

abstract class Context
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var string
     */
    private $format;

    /**
     * @var VisitorInterface
     */
    private $visitor;

    /**
     * @var GraphNavigatorInterface
     */
    private $navigator;

    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /** @var ExclusionStrategyInterface */
    private $exclusionStrategy;

    /**
     * @var bool
     */
    private $initialized = false;

    /** @var MetadataStack */
    private $metadataStack;

    public function __construct()
    {
        $this->metadataStack = new MetadataStack();
    }

    public function initialize(string $format, VisitorInterface $visitor, GraphNavigatorInterface $navigator, MetadataFactoryInterface $factory): void
    {
        if ($this->initialized) {
            throw new LogicException('This context was already initialized, and cannot be re-used.');
        }

        $this->format = $format;
        $this->visitor = $visitor;
        $this->navigator = $navigator;
        $this->metadataFactory = $factory;
        $this->metadataStack = new MetadataStack();

        if (isset($this->attributes['groups'])) {
            $this->addExclusionStrategy(new GroupsExclusionStrategy($this->attributes['groups']));
        }

        if (isset($this->attributes['version'])) {
            $this->addExclusionStrategy(new VersionExclusionStrategy($this->attributes['version']));
        }

        if (!empty($this->attributes['max_depth_checks'])) {
            $this->addExclusionStrategy(new DepthExclusionStrategy());
        }

        $this->initialized = true;
    }

    public function getMetadataFactory(): MetadataFactoryInterface
    {
        return $this->metadataFactory;
    }

    public function getVisitor(): VisitorInterface
    {
        return $this->visitor;
    }

    public function getNavigator(): GraphNavigatorInterface
    {
        return $this->navigator;
    }

    public function getExclusionStrategy(): ?ExclusionStrategyInterface
    {
        return $this->exclusionStrategy;
    }

    /**
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key];
    }

    public function hasAttribute(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setAttribute(string $key, $value): self
    {
        $this->assertMutable();
        $this->attributes[$key] = $value;

        return $this;
    }

    final protected function assertMutable(): void
    {
        if (!$this->initialized) {
            return;
        }

        throw new LogicException('This context was already initialized and is immutable; you cannot modify it anymore.');
    }

    /**
     * @return $this
     */
    public function addExclusionStrategy(ExclusionStrategyInterface $strategy): self
    {
        $this->assertMutable();

        if (null === $this->exclusionStrategy) {
            $this->exclusionStrategy = $strategy;

            return $this;
        }

        if ($this->exclusionStrategy instanceof DisjunctExclusionStrategy) {
            $this->exclusionStrategy->addStrategy($strategy);

            return $this;
        }

        $this->exclusionStrategy = new DisjunctExclusionStrategy([
            $this->exclusionStrategy,
            $strategy,
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function setVersion(string $version): self
    {
        $this->attributes['version'] = $version;

        return $this;
    }

    /**
     * @param array|string $groups
     *
     * @return $this
     */
    public function setGroups($groups): self
    {
        if (empty($groups)) {
            throw new LogicException('The groups must not be empty.');
        }

        $this->attributes['groups'] = (array) $groups;

        return $this;
    }

    /**
     * @return $this
     */
    public function enableMaxDepthChecks(): self
    {
        $this->attributes['max_depth_checks'] = true;

        return $this;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getMetadataStack(): MetadataStack
    {
        return $this->metadataStack;
    }

    abstract public function getDepth(): int;

    abstract public function getDirection(): int;

    public function close(): void
    {
        unset($this->visitor, $this->navigator);
    }
}
