<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\EventDispatcher\Subscriber;

use FastPix\Sdk\Serializer\EventDispatcher\EventDispatcherInterface;
use FastPix\Sdk\Serializer\EventDispatcher\EventSubscriberInterface;
use FastPix\Sdk\Serializer\EventDispatcher\PreSerializeEvent;

/** Optional subscriber when Doctrine Persistence/ORM/ODM or ProxyManager are available. */
final class DoctrineProxySubscriber implements EventSubscriberInterface
{
    private const PROXY = 'Doctrine\Persistence\Proxy';
    private const LEGACY_PROXY = 'Doctrine\Common\Persistence\Proxy';
    private const PERSISTENT_COLLECTION = 'Doctrine\ORM\PersistentCollection';
    private const MONGODB_COLLECTION = 'Doctrine\ODM\MongoDB\PersistentCollection';
    private const PHPCR_COLLECTION = 'Doctrine\ODM\PHPCR\PersistentCollection';
    private const LAZY_LOADING = 'ProxyManager\Proxy\LazyLoadingInterface';

    /**
     * @var bool
     */
    private $skipVirtualTypeInit;

    /**
     * @var bool
     */
    private $initializeExcluded;

    public function __construct(bool $skipVirtualTypeInit = true, bool $initializeExcluded = false)
    {
        $this->skipVirtualTypeInit = $skipVirtualTypeInit;
        $this->initializeExcluded = $initializeExcluded;
    }

    public function onPreSerialize(PreSerializeEvent $event): void
    {
        $object = $event->getObject();
        $type = $event->getType();

        // If the set type name is not an actual class, but a faked type for which a custom handler exists, we do not
        // modify it with this subscriber. Also, we forgo autoloading here as an instance of this type is already created,
        // so it must be loaded if its a real class.
        $virtualType = !class_exists($type['name'], false);

        if ($this->isPersistentCollection($object)) {
            if (!$virtualType) {
                $event->setType('ArrayCollection');
            }

            return;
        }

        if (($this->skipVirtualTypeInit && $virtualType) || !$this->isProxy($object)) {
            return;
        }

        // do not initialize the proxy if is going to be excluded by-class by some exclusion strategy
        if (false === $this->initializeExcluded && !$virtualType && $this->isExcludedByClass($event, $object)) {
            return;
        }

        $this->initializeProxy($object);

        if (!$virtualType) {
            $event->setType(get_parent_class($object), $type['params']);
        }
    }

    private function isPersistentCollection(object $object): bool
    {
        return (class_exists(self::PERSISTENT_COLLECTION) && $object instanceof \Doctrine\ORM\PersistentCollection)
            || (class_exists(self::MONGODB_COLLECTION) && $object instanceof \Doctrine\ODM\MongoDB\PersistentCollection)
            || (class_exists(self::PHPCR_COLLECTION) && $object instanceof \Doctrine\ODM\PHPCR\PersistentCollection);
    }

    private function isProxy(object $object): bool
    {
        return (interface_exists(self::PROXY) && $object instanceof \Doctrine\Persistence\Proxy)
            || (class_exists(self::LAZY_LOADING) && $object instanceof \ProxyManager\Proxy\LazyLoadingInterface);
    }

    private function isExcludedByClass(PreSerializeEvent $event, object $object): bool
    {
        $context = $event->getContext();
        $exclusionStrategy = $context->getExclusionStrategy();
        $metadata = $context->getMetadataFactory()->getMetadataForClass(get_parent_class($object));

        return null !== $metadata
            && null !== $exclusionStrategy
            && $exclusionStrategy->shouldSkipClass($metadata, $context);
    }

    private function initializeProxy(object $object): void
    {
        if (class_exists(self::LAZY_LOADING) && $object instanceof \ProxyManager\Proxy\LazyLoadingInterface) {
            $object->initializeProxy();
        } elseif (interface_exists(self::PROXY) && $object instanceof \Doctrine\Persistence\Proxy) {
            $object->__load();
        }
    }

    public function onPreSerializeTypedProxy(PreSerializeEvent $event, string $eventName, string $class, string $format, EventDispatcherInterface $dispatcher): void
    {
        $type = $event->getType();
        // is a virtual type? then there is no need to change the event name
        if (!class_exists($type['name'], false)) {
            return;
        }

        $object = $event->getObject();
        if (interface_exists(self::PROXY) && $object instanceof \Doctrine\Persistence\Proxy) {
            $parentClassName = get_parent_class($object);

            // check if this is already a re-dispatch
            if (strtolower($class) !== strtolower($parentClassName)) {
                $event->stopPropagation();
                $newEvent = new PreSerializeEvent($event->getContext(), $object, ['name' => $parentClassName, 'params' => $type['params']]);
                $dispatcher->dispatch($eventName, $parentClassName, $format, $newEvent);

                // update the type in case some listener changed it
                $newType = $newEvent->getType();
                $event->setType($newType['name'], $newType['params']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        $events = [];
        if (interface_exists(self::PROXY)) {
            $events[] = ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerializeTypedProxy', 'interface' => self::PROXY];
            $events[] = ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => self::PROXY];
        }
        if (interface_exists(self::LEGACY_PROXY)) {
            $events[] = ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerializeTypedProxy', 'interface' => self::LEGACY_PROXY];
            $events[] = ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => self::LEGACY_PROXY];
        }
        foreach ([self::PERSISTENT_COLLECTION, self::MONGODB_COLLECTION, self::PHPCR_COLLECTION] as $class) {
            if (class_exists($class)) {
                $events[] = ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => $class];
            }
        }
        if (class_exists(self::LAZY_LOADING)) {
            $events[] = ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => self::LAZY_LOADING];
        }

        return $events;
    }
}
