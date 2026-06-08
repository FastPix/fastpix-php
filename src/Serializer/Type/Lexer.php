<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Type;

use Doctrine\Common\Lexer\AbstractLexer;
use FastPix\Sdk\Serializer\Type\Exception\SyntaxError;

/**
 * @internal
 */
final class Lexer extends AbstractLexer
{
    public const T_UNKNOWN = 0;
    public const T_INTEGER = 1;
    public const T_STRING = 2;
    public const T_FLOAT = 3;
    public const T_ARRAY_START = 4;
    public const T_ARRAY_END = 5;
    public const T_COMMA = 6;
    public const T_TYPE_START = 7;
    public const T_TYPE_END = 8;
    public const T_IDENTIFIER = 9;
    public const T_NULL = 10;

    public function parse(string $type)
    {
        try {
            return $this->getType($type);
        } catch (\Throwable $e) {
            throw new SyntaxError($e->getMessage(), 0, $e);
        }
    }

    protected function getCatchablePatterns(): array
    {
        return [
            '[a-z][a-z_\\\\0-9]*', // identifier or qualified name
            "'(?:[^']|'')*'", // single quoted strings
            '(?:[0-9]+(?:[\.][0-9]+)*)(?:e[+-]?[0-9]+)?', // numbers
            '"(?:[^"]|"")*"', // double quoted strings
            '<',
            '>',
            '\\[',
            '\\]',
        ];
    }

    protected function getNonCatchablePatterns(): array
    {
        return ['\s+'];
    }

    /**
     * {@inheritDoc}
     *
     * @return int|string|null
     */
    protected function getType(&$value)
    {
        // Recognize numeric values
        if (is_numeric($value)) {
            return $this->getNumericType($value);
        }

        // Recognize quoted strings
        if ("'" === $value[0] || '"' === $value[0]) {
            $quote = $value[0];
            $value = str_replace($quote . $quote, $quote, substr($value, 1, strlen($value) - 2));

            return self::T_STRING;
        }

        return $this->getSymbolType($value);
    }

    private function getNumericType(string $value): int
    {
        if (false !== strpos($value, '.') || false !== stripos($value, 'e')) {
            return self::T_FLOAT;
        }

        return self::T_INTEGER;
    }

    private function getSymbolType(string $value): int
    {
        // Recognize identifiers, aliased or qualified names (null being a special keyword)
        if (ctype_alpha($value[0]) || '\\' === $value[0]) {
            return 'null' === $value ? self::T_NULL : self::T_IDENTIFIER;
        }

        static $simpleTokens = [
            ',' => self::T_COMMA,
            '>' => self::T_TYPE_END,
            '<' => self::T_TYPE_START,
            ']' => self::T_ARRAY_END,
            '[' => self::T_ARRAY_START,
        ];

        return $simpleTokens[$value] ?? self::T_UNKNOWN;
    }
}
