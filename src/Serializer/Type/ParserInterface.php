<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Type;

interface ParserInterface
{
    public function parse(string $type): array;
}
