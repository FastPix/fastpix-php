<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\EventDispatcher\Subscriber;

use FastPix\Sdk\Serializer\EventDispatcher\EventSubscriberInterface;
use FastPix\Sdk\Serializer\EventDispatcher\PreSerializeEvent;

final class EnumSubscriber implements EventSubscriberInterface
{
    public function onPreSerializeEnum(PreSerializeEvent $event): void
    {
        $type = $event->getType();

        if (isset($type['name']) && ('enum' === $type['name'] || !is_a($type['name'], \UnitEnum::class, true))) {
            return;
        }

        $object = $event->getObject();
        $params = [get_class($object), $object instanceof \BackedEnum ? 'value' : 'name'];
        $event->setType('enum', $params);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerializeEnum', 'interface' => \UnitEnum::class],
        ];
    }
}
