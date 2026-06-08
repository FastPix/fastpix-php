<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata\Driver\DocBlockDriver;

use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;

/**
 * Resolves short type hints to fully qualified class names using a class' use
 * statements and PHPStan type aliases.
 *
 * @internal
 */
final class UseStatementClassResolver
{
    /** resolve single use statements */
    private const SINGLE_USE_STATEMENTS_REGEX = '/^[^\S\r\n]*use[\s]*([^;\n]*)[\s]*;$/m';

    /** resolve group use statements */
    private const GROUP_USE_STATEMENTS_REGEX = '/^[^\S\r\n]*use[[\s]*([^;\n]*)[\s]*{([a-zA-Z0-9\s\n\r,]*)};$/m';
    private const GLOBAL_NAMESPACE_PREFIX = '\\';
    private const PHPSTAN_ARRAY_SHAPE = '/^([^\s]*) array{.*/m';
    private const PHPSTAN_ARRAY_TYPE = '/^([^\s]*) array<(.*)>/m';

    /**
     * @var PhpDocParser
     */
    private $phpDocParser;

    /**
     * @var Lexer
     */
    private $lexer;

    public function __construct()
    {
        $constExprParser = new ConstExprParser();
        $typeParser = new TypeParser($constExprParser);

        $this->phpDocParser = new PhpDocParser($typeParser, $constExprParser);
        $this->lexer = new Lexer();
    }

    /**
     * Attempts to resolve the fully qualified type from the given node. If the node is not suitable for type
     * retrieval, an exception is thrown.
     *
     * @param TypeNode $typeNode
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function resolveTypeFromTypeNode(TypeNode $typeNode, $reflector): string
    {
        if (!($typeNode instanceof IdentifierTypeNode)) {
            throw new \InvalidArgumentException(sprintf("Can't use unsupported type %s for collection in %s:%s", (string) $typeNode, $reflector->getDeclaringClass()->getName(), $reflector->getName()));
        }

        return $this->resolveType($typeNode->name, $reflector);
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function resolveType(string $typeHint, $reflector): string
    {
        if (!$this->hasGlobalNamespacePrefix($typeHint) && !$this->isPrimitiveType($typeHint)) {
            $typeHint = $this->expandClassNameUsingUseStatements($typeHint, $this->getDeclaringClassOrTrait($reflector), $reflector);
        }

        return ltrim($typeHint, '\\');
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function expandClassNameUsingUseStatements(string $typeHint, \ReflectionClass $declaringClass, $reflector): string
    {
        $expandedClassName = $declaringClass->getNamespaceName() . '\\' . $typeHint;
        if ($this->isClassOrInterface($expandedClassName)) {
            return $expandedClassName;
        }

        $resolved = $this->resolveFromUseStatements($declaringClass, $typeHint)
            ?? $this->resolvePhpstanArrayType($declaringClass, $typeHint, $reflector)
            ?? ($this->isClassOrInterface($typeHint) ? $typeHint : null);

        if (null === $resolved) {
            throw new \InvalidArgumentException(sprintf("Can't use incorrect type %s for collection in %s:%s", $typeHint, $declaringClass->getName(), $reflector->getName()));
        }

        return $resolved;
    }

    private function resolveFromUseStatements(\ReflectionClass $declaringClass, string $typeHint): ?string
    {
        $classContents = file_get_contents($declaringClass->getFileName());
        $foundUseStatements = array_merge(
            $this->gatherSingleUseStatements($classContents),
            $this->gatherGroupUseStatements($classContents),
        );

        foreach ($foundUseStatements as $statementClassName) {
            $alias = explode('as', $statementClassName);
            if (array_key_exists(1, $alias) && trim($alias[1]) === $typeHint) {
                return trim($alias[0]);
            }

            if ($this->endsWith($statementClassName, $typeHint)) {
                return $statementClassName;
            }
        }

        return null;
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function resolvePhpstanArrayType(\ReflectionClass $declaringClass, string $typeHint, $reflector): ?string
    {
        if (!$declaringClass->getDocComment()) {
            return null;
        }

        return $this->getPhpstanArrayType($declaringClass, $typeHint, $reflector);
    }

    private function endsWith(string $statementClassToCheck, string $typeHintToSearchFor): bool
    {
        $typeHintToSearchFor = '\\' . $typeHintToSearchFor;

        return substr($statementClassToCheck, -strlen($typeHintToSearchFor)) === $typeHintToSearchFor;
    }

    private function isPrimitiveType(string $type): bool
    {
        return in_array($type, ['int', 'integer', 'float', 'bool', 'boolean', 'double', 'string']);
    }

    private function hasGlobalNamespacePrefix(string $typeHint): bool
    {
        return self::GLOBAL_NAMESPACE_PREFIX === $typeHint[0];
    }

    private function gatherGroupUseStatements(string $classContents): array
    {
        $foundUseStatements = [];
        preg_match_all(self::GROUP_USE_STATEMENTS_REGEX, $classContents, $foundGroupUseStatements);
        for ($useStatementIndex = 0; $useStatementIndex < count($foundGroupUseStatements[0]); $useStatementIndex++) {
            foreach (explode(',', $foundGroupUseStatements[2][$useStatementIndex]) as $singleUseStatement) {
                $foundUseStatements[] = trim($foundGroupUseStatements[1][$useStatementIndex]) . trim($singleUseStatement);
            }
        }

        return $foundUseStatements;
    }

    private function gatherSingleUseStatements(string $classContents): array
    {
        $foundUseStatements = [];
        preg_match_all(self::SINGLE_USE_STATEMENTS_REGEX, $classContents, $foundSingleUseStatements);
        for ($useStatementIndex = 0; $useStatementIndex < count($foundSingleUseStatements[0]); $useStatementIndex++) {
            $foundUseStatements[] = trim($foundSingleUseStatements[1][$useStatementIndex]);
        }

        return $foundUseStatements;
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function getDeclaringClassOrTrait($reflector): \ReflectionClass
    {
        foreach ($reflector->getDeclaringClass()->getTraits() as $trait) {
            foreach ($trait->getProperties() as $traitProperty) {
                if ($traitProperty->getName() === $reflector->getName()) {
                    return $this->getDeclaringClassOrTrait($traitProperty);
                }
            }
        }

        return $reflector->getDeclaringClass();
    }

    private function isClassOrInterface(string $typeHint): bool
    {
        return class_exists($typeHint) || interface_exists($typeHint);
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function getPhpstanArrayType(\ReflectionClass $declaringClass, string $typeHint, $reflector): ?string
    {
        $tokens = $this->lexer->tokenize($declaringClass->getDocComment());
        $phpDocNode = $this->phpDocParser->parse(new TokenIterator($tokens));

        foreach ($phpDocNode->children as $node) {
            if (!($node instanceof PhpDocTagNode)) {
                continue;
            }

            if ($node->value instanceof TypeAliasTagValueNode && $node->value->alias === $typeHint) {
                return $this->resolvePhpstanAlias($node->value->__toString(), $reflector);
            }

            if ($node->value instanceof TypeAliasImportTagValueNode) {
                $importedFromFqn = $this->resolveType($node->value->importedFrom->name, $reflector);

                return $this->getPhpstanArrayType(
                    new \ReflectionClass($importedFromFqn),
                    $node->value->importedAlias,
                    $reflector,
                );
            }
        }

        return null;
    }

    /**
     * @param \ReflectionMethod|\ReflectionProperty $reflector
     */
    private function resolvePhpstanAlias(string $phpstanType, $reflector): ?string
    {
        preg_match(self::PHPSTAN_ARRAY_SHAPE, $phpstanType, $foundShape);
        if (isset($foundShape[0])) {
            return 'array';
        }

        preg_match(self::PHPSTAN_ARRAY_TYPE, $phpstanType, $foundType);
        if (!isset($foundType[0])) {
            return null;
        }

        $self = $this;
        $types = explode(',', $foundType[2]);

        return sprintf('array<%s>', implode(
            ',',
            array_map(static fn (string $type) => $self->resolveType(trim($type), $reflector), $types),
        ));
    }
}
