<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Expression;

/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
interface ExpressionEvaluatorInterface
{
    /**
     * @return mixed
     */
    public function evaluate(string $expression, array $data = []);
}
