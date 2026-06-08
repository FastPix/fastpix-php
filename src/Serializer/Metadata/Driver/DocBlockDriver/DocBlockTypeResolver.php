<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata\Driver\DocBlockDriver;

use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;

/**
 * @internal
 */
final class DocBlockTypeResolver
{
    /**
     * @var PhpDocParser
     */
    protected $phpDocParser;

    /**
     * @var Lexer
     */
    protected $lexer;

    /**
     * @var UseStatementClassResolver
     */
    private $classResolver;

    public function __construct()
    {
        $constExprParser = new ConstExprParser();
        $typeParser = new TypeParser($constExprParser);

        $this->phpDocParser = new PhpDocParser($typeParser, $constExprParser);
        $this->lexer = new Lexer();
        $this->classResolver = new UseStatementClassResolver();
    }

    /**
     * Attempts to retrieve additional type information from a PhpDoc block. Throws in case of ambiguous type
     * information and will return null if no helpful type information could be retrieved.
     *
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return string|null
     */
    public function getMethodDocblockTypeHint(\ReflectionMethod $reflectionMethod): ?string
    {
        return $this->getDocBlocTypeHint($reflectionMethod);
    }

    /**
     * Attempts to retrieve additional type information from a PhpDoc block. Throws in case of ambiguous type
     * information and will return null if no helpful type information could be retrieved.
     *
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return string|null
     */
    public function getPropertyDocblockTypeHint(\ReflectionProperty $reflectionProperty): ?string
    {
        return $this->getDocBlocTypeHint($reflectionProperty);
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     *
     * @return string|null
     */
    private function getDocBlocTypeHint($reflector): ?string
    {
        $types = $this->resolveTypeFromDocblock($reflector);

        // The PhpDoc contains no type information, or multiple non-null types which
        // produces ambiguity when deserializing.
        if (empty($types) || count($types) > 1) {
            return null;
        }

        // Only one type is left, so we only need to differentiate between arrays, generics and other types.
        return $this->resolveSingleType($types[0], $reflector);
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function resolveSingleType(TypeNode $type, $reflector): ?string
    {
        // Simple array without concrete type: array | list
        if ($this->isSimpleType($type, 'array') || $this->isSimpleType($type, 'list')) {
            return null;
        }

        // Normal array syntax: Product[] | \Foo\Bar\Product[]
        if ($type instanceof ArrayTypeNode) {
            return 'array<' . $this->classResolver->resolveTypeFromTypeNode($type->type, $reflector) . '>';
        }

        // Generic array syntax (array<Product>, array<int,Product>), primitives and class names.
        return $type instanceof GenericTypeNode
            ? $this->resolveGenericArrayType($type, $reflector)
            : $this->classResolver->resolveTypeFromTypeNode($type, $reflector);
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function resolveGenericArrayType(GenericTypeNode $type, $reflector): string
    {
        if ($this->isSimpleType($type->type, 'array')) {
            $resolvedTypes = array_map(fn (TypeNode $node) => $this->classResolver->resolveTypeFromTypeNode($node, $reflector), $type->genericTypes);

            return 'array<' . implode(',', $resolvedTypes) . '>';
        }

        if ($this->isSimpleType($type->type, 'list')) {
            $resolvedTypes = array_map(fn (TypeNode $node) => $this->classResolver->resolveTypeFromTypeNode($node, $reflector), $type->genericTypes);

            return 'array<int, ' . implode(',', $resolvedTypes) . '>';
        }

        throw new \InvalidArgumentException(sprintf("Can't use non-array generic type %s for collection in %s:%s", (string) $type->type, $reflector->getDeclaringClass()->getName(), $reflector->getName()));
    }

    /**
     * Returns a flat list of types of the given var tags. Union types are flattened as well.
     *
     * @param ReturnTagValueNode[]|VarTagValueNode[] $tagValues
     *
     * @return TypeNode[]
     */
    private function flattenTagValueTypes(array $tagValues): array
    {
        if ([] === $tagValues) {
            return [];
        }

        return array_merge(...array_map(static function ($node) {
            if ($node->type instanceof UnionTypeNode) {
                return $node->type->types;
            }

            return [$node->type];
        }, $tagValues));
    }

    /**
     * Returns a flat list of types of the given param tags. Union types are flattened as well.
     *
     * @param ParamTagValueNode[] $varTagValues
     *
     * @return TypeNode[]
     */
    private function flattenParamTagValueTypes(string $parameterName, array $varTagValues): array
    {
        if ([] === $varTagValues) {
            return [];
        }

        $parameterName = sprintf('$%s', $parameterName);
        $types = [];
        foreach ($varTagValues as $node) {
            if ($parameterName !== $node->parameterName) {
                continue;
            }

            $types[] = $node->type;
        }

        return $types;
    }

    /**
     * Filters the null type from the given types array. If no null type is found, the array is returned unchanged.
     *
     * @param TypeNode[] $types
     *
     * @return TypeNode[]
     */
    private function filterNullFromTypes(array $types): array
    {
        return array_values(array_filter(array_map(fn (TypeNode $node) => $this->isNullType($node) ? null : $node, $types)));
    }

    /**
     * Determines if the given type is a null type.
     *
     * @param TypeNode $typeNode
     *
     * @return bool
     */
    private function isNullType(TypeNode $typeNode): bool
    {
        return $this->isSimpleType($typeNode, 'null');
    }

    /**
     * Determines if the given node represents a simple type.
     *
     * @param TypeNode $typeNode
     * @param string $simpleType
     *
     * @return bool
     */
    private function isSimpleType(TypeNode $typeNode, string $simpleType): bool
    {
        return $typeNode instanceof IdentifierTypeNode && $typeNode->name === $simpleType;
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function resolveTypeFromDocblock($reflector): array
    {
        $docComment = $reflector->getDocComment();

        if (!$docComment) {
            return $this->resolvePromotedPropertyTypes($reflector);
        }

        // First we tokenize the PhpDoc comment and parse the tokens into a PhpDocNode.
        $tokens = $this->lexer->tokenize($docComment);
        $phpDocNode = $this->phpDocParser->parse(new TokenIterator($tokens));

        if ($reflector instanceof \ReflectionProperty) {
            // Then we retrieve a flattened list of annotated types excluding null.
            $tagValues = $phpDocNode->getVarTagValues();
        } else {
            // Then we retrieve a flattened list of annotated types including null.
            $tagValues = $phpDocNode->getReturnTagValues();
        }

        return $this->filterNullFromTypes($this->flattenTagValueTypes($tagValues));
    }

    /**
     * Resolves types from the constructor docblock for promoted properties that have no own docblock.
     *
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     *
     * @return TypeNode[]
     */
    private function resolvePromotedPropertyTypes($reflector): array
    {
        if (!(PHP_VERSION_ID >= 80000 && $reflector instanceof \ReflectionProperty && $reflector->isPromoted())) {
            return [];
        }

        $constructor = $reflector->getDeclaringClass()->getConstructor();
        $docComment = $constructor ? $constructor->getDocComment() : null;
        if (!$docComment) {
            return [];
        }

        $tokens = $this->lexer->tokenize($docComment);
        $phpDocNode = $this->phpDocParser->parse(new TokenIterator($tokens));

        return $this->flattenParamTagValueTypes($reflector->getName(), $phpDocNode->getParamTagValues());
    }
}
