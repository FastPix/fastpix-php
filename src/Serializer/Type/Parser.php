<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Type;

use FastPix\Sdk\Serializer\Type\Exception\SyntaxError;

/**
 * @internal
 */
final class Parser implements ParserInterface
{
    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * @var bool
     */
    private $root = true;

    public function parse(string $string): array
    {
        $this->lexer = new Lexer();
        $this->lexer->setInput($string);
        $this->lexer->moveNext();

        return $this->visit();
    }

    /**
     * @return mixed
     */
    private function visit()
    {
        $this->lexer->moveNext();

        if (!$this->lexer->token) {
            throw new SyntaxError(
                'Syntax error, unexpected end of stream',
            );
        }

        $type = $this->lexer->token->type;

        if (Lexer::T_IDENTIFIER === $type) {
            return $this->visitIdentifier();
        }

        if (!$this->root && Lexer::T_ARRAY_START === $type) {
            return $this->visitArrayType();
        }

        return $this->visitScalar();
    }

    /**
     * @return mixed
     */
    private function visitIdentifier()
    {
        if ($this->lexer->isNextToken(Lexer::T_TYPE_START)) {
            return $this->visitCompoundType();
        }

        if ($this->lexer->isNextToken(Lexer::T_ARRAY_START)) {
            return $this->visitArrayType();
        }

        return $this->visitSimpleType();
    }

    /**
     * @return float|int|string|null
     */
    private function visitScalar()
    {
        $token = $this->lexer->token;

        if (Lexer::T_NULL === $token->type) {
            return null;
        }

        $converters = [
            Lexer::T_FLOAT => 'floatval',
            Lexer::T_INTEGER => 'intval',
            Lexer::T_STRING => 'strval',
        ];

        if (isset($converters[$token->type])) {
            return $converters[$token->type]($token->value);
        }

        throw new SyntaxError(sprintf(
            'Syntax error, unexpected "%s" (%s)',
            $token->value,
            $this->getConstant($token->type),
        ));
    }

    /**
     * @return string|mixed[]
     */
    private function visitSimpleType()
    {
        $value = $this->lexer->token->value;

        return ['name' => $value, 'params' => []];
    }

    private function visitCompoundType(): array
    {
        $this->root = false;
        $name = $this->lexer->token->value;
        $this->match(Lexer::T_TYPE_START);

        $params = [];
        if (!$this->lexer->isNextToken(Lexer::T_TYPE_END)) {
            while (true) {
                $params[] = $this->visit();

                if ($this->lexer->isNextToken(Lexer::T_TYPE_END)) {
                    break;
                }

                $this->match(Lexer::T_COMMA);
            }
        }

        $this->match(Lexer::T_TYPE_END);

        return [
            'name' => $name,
            'params' => $params,
        ];
    }

    private function visitArrayType(): array
    {
        /*
         * Here we should call $this->match(Lexer::T_ARRAY_START); to make it clean
         * but the token has already been consumed by moveNext() in visit()
         */

        $params = [];
        if (!$this->lexer->isNextToken(Lexer::T_ARRAY_END)) {
            while (true) {
                $params[] = $this->visit();
                if ($this->lexer->isNextToken(Lexer::T_ARRAY_END)) {
                    break;
                }

                $this->match(Lexer::T_COMMA);
            }
        }

        $this->match(Lexer::T_ARRAY_END);

        return $params;
    }

    private function match(int $token): void
    {
        if (!$this->lexer->lookahead) {
            throw new SyntaxError(
                sprintf('Syntax error, unexpected end of stream, expected %s', $this->getConstant($token)),
            );
        }

        if ($this->lexer->lookahead->type === $token) {
            $this->lexer->moveNext();

            return;
        }

        throw new SyntaxError(sprintf(
            'Syntax error, unexpected "%s" (%s), expected was %s',
            $this->lexer->lookahead->value,
            $this->getConstant($this->lexer->lookahead->type),
            $this->getConstant($token),
        ));
    }

    private function getConstant(int $value): string
    {
        static $names = [
            Lexer::T_UNKNOWN => 'T_UNKNOWN',
            Lexer::T_INTEGER => 'T_INTEGER',
            Lexer::T_STRING => 'T_STRING',
            Lexer::T_FLOAT => 'T_FLOAT',
            Lexer::T_ARRAY_START => 'T_ARRAY_START',
            Lexer::T_ARRAY_END => 'T_ARRAY_END',
            Lexer::T_COMMA => 'T_COMMA',
            Lexer::T_TYPE_START => 'T_TYPE_START',
            Lexer::T_TYPE_END => 'T_TYPE_END',
            Lexer::T_IDENTIFIER => 'T_IDENTIFIER',
            Lexer::T_NULL => 'T_NULL',
        ];

        return $names[$value] ?? (string) $value;
    }
}
