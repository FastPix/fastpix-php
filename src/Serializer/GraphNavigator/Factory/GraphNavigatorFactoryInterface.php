<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\GraphNavigator\Factory;

use FastPix\Sdk\Serializer\GraphNavigatorInterface;

interface GraphNavigatorFactoryInterface
{
    public function getGraphNavigator(): GraphNavigatorInterface;
}
