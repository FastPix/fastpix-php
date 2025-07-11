<?php

declare(strict_types=1);

namespace Speakeasy\Serializer\EventDispatcher;

use Speakeasy\Serializer\Context;

class ObjectEvent extends Event
{
    /**
     * @var mixed
     */
    private $object;

    /**
     * @param mixed $object
     */
    public function __construct(Context $context, $object, array $type)
    {
        parent::__construct($context, $type);

        $this->object = $object;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }
}
