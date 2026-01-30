<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Builder;

use FastPix\Sdk\Serializer\Exception\LogicException;
use Metadata\Driver\DriverInterface;

final class CallbackDriverFactory implements DriverFactoryInterface
{
    /**
     * @var callable
     * @phpstan-var callable(array<string, string> $metadataDirs, object|null $reader): DriverInterface
     */
    private $callback;

    /**
     * @phpstan-param callable(array<string, string> $metadataDirs, object|null $reader): DriverInterface $callable
     */
    public function __construct(callable $callable)
    {
        $this->callback = $callable;
    }

    public function createDriver(array $metadataDirs, ?object $reader = null): DriverInterface
    {
        $driver = \call_user_func($this->callback, $metadataDirs, $reader);
        if (!$driver instanceof DriverInterface) {
            throw new LogicException('The callback must return an instance of DriverInterface.');
        }

        return $driver;
    }
}
