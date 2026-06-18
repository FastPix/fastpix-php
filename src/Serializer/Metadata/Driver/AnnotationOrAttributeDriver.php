<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata\Driver;

use FastPix\Sdk\Serializer\Annotation\Accessor;
use FastPix\Sdk\Serializer\Annotation\AccessorOrder;
use FastPix\Sdk\Serializer\Annotation\AccessType;
use FastPix\Sdk\Serializer\Annotation\Discriminator;
use FastPix\Sdk\Serializer\Annotation\Exclude;
use FastPix\Sdk\Serializer\Annotation\ExclusionPolicy;
use FastPix\Sdk\Serializer\Annotation\Expose;
use FastPix\Sdk\Serializer\Annotation\Groups;
use FastPix\Sdk\Serializer\Annotation\Inline;
use FastPix\Sdk\Serializer\Annotation\MaxDepth;
use FastPix\Sdk\Serializer\Annotation\PostDeserialize;
use FastPix\Sdk\Serializer\Annotation\PostSerialize;
use FastPix\Sdk\Serializer\Annotation\PreSerialize;
use FastPix\Sdk\Serializer\Annotation\ReadOnlyProperty;
use FastPix\Sdk\Serializer\Annotation\SerializedName;
use FastPix\Sdk\Serializer\Annotation\SerializerAttribute;
use FastPix\Sdk\Serializer\Annotation\Since;
use FastPix\Sdk\Serializer\Annotation\SkipWhenEmpty;
use FastPix\Sdk\Serializer\Annotation\SkipWhenNull;
use FastPix\Sdk\Serializer\Annotation\Type;
use FastPix\Sdk\Serializer\Annotation\UnionDiscriminator;
use FastPix\Sdk\Serializer\Annotation\Until;
use FastPix\Sdk\Serializer\Annotation\VirtualProperty;
use FastPix\Sdk\Serializer\Annotation\XmlAttribute;
use FastPix\Sdk\Serializer\Annotation\XmlAttributeMap;
use FastPix\Sdk\Serializer\Annotation\XmlDiscriminator;
use FastPix\Sdk\Serializer\Annotation\XmlElement;
use FastPix\Sdk\Serializer\Annotation\XmlKeyValuePairs;
use FastPix\Sdk\Serializer\Annotation\XmlList;
use FastPix\Sdk\Serializer\Annotation\XmlMap;
use FastPix\Sdk\Serializer\Annotation\XmlNamespace;
use FastPix\Sdk\Serializer\Annotation\XmlRoot;
use FastPix\Sdk\Serializer\Annotation\XmlValue;
use FastPix\Sdk\Serializer\Exception\InvalidMetadataException;
use FastPix\Sdk\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use FastPix\Sdk\Serializer\Metadata\ClassMetadata;
use FastPix\Sdk\Serializer\Metadata\ExpressionPropertyMetadata;
use FastPix\Sdk\Serializer\Metadata\PropertyMetadata;
use FastPix\Sdk\Serializer\Metadata\VirtualPropertyMetadata;
use FastPix\Sdk\Serializer\Naming\PropertyNamingStrategyInterface;
use FastPix\Sdk\Serializer\Type\Parser;
use FastPix\Sdk\Serializer\Type\ParserInterface;
use Metadata\ClassMetadata as BaseClassMetadata;
use Metadata\Driver\DriverInterface;
use Metadata\MethodMetadata;

class AnnotationOrAttributeDriver implements DriverInterface
{
    use ExpressionMetadataTrait;

    /**
     * @var ParserInterface
     */
    private $typeParser;

    /**
     * @var PropertyNamingStrategyInterface
     */
    private $namingStrategy;

    /**
     * @var object|null Doctrine\Common\Annotations\Reader when provided (optional; PHP 8+ uses attributes)
     */
    private $reader;

    public function __construct(PropertyNamingStrategyInterface $namingStrategy, ?ParserInterface $typeParser = null, ?CompilableExpressionEvaluatorInterface $expressionEvaluator = null, ?object $reader = null)
    {
        $this->typeParser = $typeParser ?: new Parser();
        $this->namingStrategy = $namingStrategy;
        $this->expressionEvaluator = $expressionEvaluator;
        $this->reader = $reader;
    }

    public function loadMetadataForClass(\ReflectionClass $class): ?BaseClassMetadata
    {
        $name = $class->name;
        $classMetadata = new ClassMetadata($name);

        $fileResource = $class->getFilename();
        if (false !== $fileResource) {
            $classMetadata->fileResources[] = $fileResource;
        }

        $propertiesMetadata = [];
        $propertiesAnnotations = [];

        $classConfig = $this->loadClassAnnotations($class, $classMetadata, $propertiesMetadata, $propertiesAnnotations);
        $this->loadVirtualMethodProperties($class, $name, $classMetadata, $propertiesMetadata, $propertiesAnnotations);

        if (!$classConfig['excludeAll']) {
            $this->collectProperties($class, $name, $propertiesMetadata, $propertiesAnnotations);

            foreach ($propertiesMetadata as $propertyKey => $propertyMetadata) {
                $this->configurePropertyMetadata($propertyMetadata, $propertiesAnnotations[$propertyKey], $classMetadata, $classConfig);
            }
        }

        return $classMetadata;
    }

    /**
     * Reads class-level annotations/attributes into the class metadata and returns
     * the class-level configuration consumed while building property metadata.
     *
     * @param PropertyMetadata[] $propertiesMetadata
     * @param array[]            $propertiesAnnotations
     *
     * @return array{exclusionPolicy: string, excludeAll: bool, classAccessType: string, readOnlyClass: bool}
     */
    private function loadClassAnnotations(\ReflectionClass $class, ClassMetadata $classMetadata, array &$propertiesMetadata, array &$propertiesAnnotations): array
    {
        $name = $class->name;
        $config = [
            'exclusionPolicy' => ExclusionPolicy::NONE,
            'excludeAll' => false,
            'classAccessType' => PropertyMetadata::ACCESS_TYPE_PROPERTY,
            'readOnlyClass' => false,
        ];

        foreach ($this->getClassAnnotations($class) as $annot) {
            switch (true) {
                case $annot instanceof ExclusionPolicy:
                    $config['exclusionPolicy'] = $annot->policy;
                    break;
                case $annot instanceof XmlRoot:
                    $classMetadata->xmlRootName = $annot->name;
                    $classMetadata->xmlRootNamespace = $annot->namespace;
                    $classMetadata->xmlRootPrefix = $annot->prefix;
                    break;
                case $annot instanceof XmlNamespace:
                    $classMetadata->registerNamespace($annot->uri, $annot->prefix);
                    break;
                case $annot instanceof Exclude:
                    if (null !== $annot->if) {
                        $classMetadata->excludeIf = $this->parseExpression($annot->if);
                    } else {
                        $config['excludeAll'] = true;
                    }
                    break;
                case $annot instanceof AccessType:
                    $config['classAccessType'] = $annot->type;
                    break;
                case $annot instanceof ReadOnlyProperty:
                    $config['readOnlyClass'] = true;
                    break;
                case $annot instanceof AccessorOrder:
                    $classMetadata->setAccessorOrder($annot->order, $annot->custom);
                    break;
                case $annot instanceof Discriminator:
                    if ($annot->disabled) {
                        $classMetadata->discriminatorDisabled = true;
                    } else {
                        $classMetadata->setDiscriminator($annot->field, $annot->map, $annot->groups);
                    }
                    break;
                case $annot instanceof XmlDiscriminator:
                    $classMetadata->xmlDiscriminatorAttribute = (bool) $annot->attribute;
                    $classMetadata->xmlDiscriminatorCData = (bool) $annot->cdata;
                    $classMetadata->xmlDiscriminatorNamespace = $annot->namespace ? (string) $annot->namespace : null;
                    break;
                case $annot instanceof VirtualProperty:
                    $propertiesMetadata[] = new ExpressionPropertyMetadata(
                        $name,
                        $annot->name,
                        $this->parseExpression($annot->exp),
                    );
                    $propertiesAnnotations[] = $annot->options;
                    break;
                default:
                    break;
            }
        }

        return $config;
    }

    /**
     * Reads method-level annotations: (de)serialization lifecycle callbacks and
     * method-based virtual properties.
     *
     * @param PropertyMetadata[] $propertiesMetadata
     * @param array[]            $propertiesAnnotations
     */
    private function loadVirtualMethodProperties(\ReflectionClass $class, string $name, ClassMetadata $classMetadata, array &$propertiesMetadata, array &$propertiesAnnotations): void
    {
        foreach ($class->getMethods() as $method) {
            if ($method->class !== $name) {
                continue;
            }

            $methodAnnotations = $this->getMethodAnnotations($method);

            foreach ($methodAnnotations as $annot) {
                if ($annot instanceof PreSerialize) {
                    $classMetadata->addPreSerializeMethod(new MethodMetadata($name, $method->name));
                    continue 2;
                } elseif ($annot instanceof PostDeserialize) {
                    $classMetadata->addPostDeserializeMethod(new MethodMetadata($name, $method->name));
                    continue 2;
                } elseif ($annot instanceof PostSerialize) {
                    $classMetadata->addPostSerializeMethod(new MethodMetadata($name, $method->name));
                    continue 2;
                } elseif ($annot instanceof VirtualProperty) {
                    $propertiesMetadata[] = new VirtualPropertyMetadata($name, $method->name);
                    $propertiesAnnotations[] = $methodAnnotations;
                    continue 2;
                }
            }
        }
    }

    /**
     * Collects the reflected properties declared directly on the class.
     *
     * @param PropertyMetadata[] $propertiesMetadata
     * @param array[]            $propertiesAnnotations
     */
    private function collectProperties(\ReflectionClass $class, string $name, array &$propertiesMetadata, array &$propertiesAnnotations): void
    {
        foreach ($class->getProperties() as $property) {
            if ($property->class !== $name || (isset($property->info) && $property->info['class'] !== $name)) {
                continue;
            }

            $propertiesMetadata[] = new PropertyMetadata($name, $property->getName());
            $propertiesAnnotations[] = $this->getPropertyAnnotations($property);
        }
    }

    /**
     * Applies the property's annotations and, unless excluded, registers it on the class metadata.
     *
     * @param array  $propertyAnnotations
     * @param array{exclusionPolicy: string, excludeAll: bool, classAccessType: string, readOnlyClass: bool} $classConfig
     */
    private function configurePropertyMetadata(PropertyMetadata $propertyMetadata, array $propertyAnnotations, ClassMetadata $classMetadata, array $classConfig): void
    {
        $isExclude = false;
        $isExpose = $propertyMetadata instanceof VirtualPropertyMetadata
            || $propertyMetadata instanceof ExpressionPropertyMetadata;
        $propertyMetadata->readOnly = $propertyMetadata->readOnly || $classConfig['readOnlyClass'];
        $accessType = $classConfig['classAccessType'];
        $accessor = [null, null];

        foreach ($propertyAnnotations as $annot) {
            $this->applyPropertyAnnotation($annot, $propertyMetadata, $accessType, $accessor, $isExclude, $isExpose);
        }

        $this->applyInlineCollection($classMetadata, $propertyMetadata);
        $this->applyVirtualPropertyName($propertyMetadata, $propertyAnnotations);

        if (!$propertyMetadata->serializedName) {
            $propertyMetadata->serializedName = $this->namingStrategy->translateName($propertyMetadata);
        }

        if (
            (ExclusionPolicy::NONE === $classConfig['exclusionPolicy'] && !$isExclude)
            || (ExclusionPolicy::ALL === $classConfig['exclusionPolicy'] && $isExpose)
        ) {
            $propertyMetadata->setAccessor($accessType, $accessor[0], $accessor[1]);
            $classMetadata->addPropertyMetadata($propertyMetadata);
        }
    }

    private function applyInlineCollection(ClassMetadata $classMetadata, PropertyMetadata $propertyMetadata): void
    {
        if (!$propertyMetadata->inline) {
            return;
        }

        $classMetadata->isList = $classMetadata->isList || PropertyMetadata::isCollectionList($propertyMetadata->type);
        $classMetadata->isMap = $classMetadata->isMap || PropertyMetadata::isCollectionMap($propertyMetadata->type);

        if ($classMetadata->isMap && $classMetadata->isList) {
            throw new InvalidMetadataException('Can not have an inline map and and inline map on the same class');
        }
    }

    /**
     * @param array $propertyAnnotations
     */
    private function applyVirtualPropertyName(PropertyMetadata $propertyMetadata, array $propertyAnnotations): void
    {
        foreach ($propertyAnnotations as $annot) {
            if ($annot instanceof VirtualProperty && null !== $annot->name) {
                $propertyMetadata->name = $annot->name;
            }
        }
    }

    /**
     * Applies a single property-level annotation to the property metadata.
     *
     * @param array{0: ?string, 1: ?string} $accessor
     */
    private function applyPropertyAnnotation(object $annot, PropertyMetadata $propertyMetadata, string &$accessType, array &$accessor, bool &$isExclude, bool &$isExpose): void
    {
        switch (true) {
            case $annot instanceof Since:
                $propertyMetadata->sinceVersion = $annot->version;
                break;
            case $annot instanceof Until:
                $propertyMetadata->untilVersion = $annot->version;
                break;
            case $annot instanceof SerializedName:
                $propertyMetadata->serializedName = $annot->name;
                break;
            case $annot instanceof SkipWhenEmpty:
                $propertyMetadata->skipWhenEmpty = true;
                break;
            case $annot instanceof SkipWhenNull:
                $propertyMetadata->skipWhenNull = true;
                break;
            case $annot instanceof Expose:
                $isExpose = true;
                if (null !== $annot->if) {
                    $propertyMetadata->excludeIf = $this->parseExpression('!(' . $annot->if . ')');
                }
                break;
            case $annot instanceof Exclude:
                if (null !== $annot->if) {
                    $propertyMetadata->excludeIf = $this->parseExpression($annot->if);
                } else {
                    $isExclude = true;
                }
                break;
            case $annot instanceof Type:
                $propertyMetadata->setType($this->typeParser->parse($annot->name));
                break;
            case $annot instanceof XmlElement:
                $propertyMetadata->xmlAttribute = false;
                $propertyMetadata->xmlElementCData = $annot->cdata;
                $propertyMetadata->xmlNamespace = $annot->namespace;
                break;
            case $annot instanceof XmlList:
                $propertyMetadata->xmlCollection = true;
                $propertyMetadata->xmlCollectionInline = $annot->inline;
                $propertyMetadata->xmlEntryName = $annot->entry;
                $propertyMetadata->xmlEntryNamespace = $annot->namespace;
                $propertyMetadata->xmlCollectionSkipWhenEmpty = $annot->skipWhenEmpty;
                break;
            case $annot instanceof XmlMap:
                $propertyMetadata->xmlCollection = true;
                $propertyMetadata->xmlCollectionInline = $annot->inline;
                $propertyMetadata->xmlEntryName = $annot->entry;
                $propertyMetadata->xmlEntryNamespace = $annot->namespace;
                $propertyMetadata->xmlKeyAttribute = $annot->keyAttribute;
                break;
            case $annot instanceof XmlKeyValuePairs:
                $propertyMetadata->xmlKeyValuePairs = true;
                break;
            case $annot instanceof XmlAttribute:
                $propertyMetadata->xmlAttribute = true;
                $propertyMetadata->xmlNamespace = $annot->namespace;
                break;
            case $annot instanceof XmlValue:
                $propertyMetadata->xmlValue = true;
                $propertyMetadata->xmlElementCData = $annot->cdata;
                break;
            case $annot instanceof AccessType:
                $accessType = $annot->type;
                break;
            case $annot instanceof ReadOnlyProperty:
                $propertyMetadata->readOnly = $annot->readOnly;
                break;
            case $annot instanceof Accessor:
                $accessor = [$annot->getter, $annot->setter];
                break;
            case $annot instanceof Groups:
                $this->applyGroups($propertyMetadata, $annot);
                break;
            case $annot instanceof Inline:
                $propertyMetadata->inline = true;
                break;
            case $annot instanceof XmlAttributeMap:
                $propertyMetadata->xmlAttributeMap = true;
                break;
            case $annot instanceof MaxDepth:
                $propertyMetadata->maxDepth = $annot->depth;
                break;
            case $annot instanceof UnionDiscriminator:
                $propertyMetadata->setType([
                    'name' => 'union',
                    'params' => [null, $annot->field, $annot->map],
                ]);
                break;
            default:
                break;
        }
    }

    private function applyGroups(PropertyMetadata $propertyMetadata, Groups $annot): void
    {
        $propertyMetadata->groups = $annot->groups;
        foreach ((array) $propertyMetadata->groups as $groupName) {
            if (false !== strpos($groupName, ',')) {
                throw new InvalidMetadataException(sprintf(
                    'Invalid group name "%s" on "%s", did you mean to create multiple groups?',
                    implode(', ', $propertyMetadata->groups),
                    $propertyMetadata->class . '->' . $propertyMetadata->name,
                ));
            }
        }
    }

    /**
     * @return list<SerializerAttribute>
     */
    protected function getClassAnnotations(\ReflectionClass $class): array
    {
        $annotations = [];

        if (PHP_VERSION_ID >= 80000) {
            $annotations = array_map(
                static fn (\ReflectionAttribute $attribute): object => $attribute->newInstance(),
                $class->getAttributes(SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF),
            );
        }

        if (null !== $this->reader) {
            $annotations = array_merge($annotations, $this->reader->getClassAnnotations($class));
        }

        return $annotations;
    }

    /**
     * @return list<SerializerAttribute>
     */
    protected function getMethodAnnotations(\ReflectionMethod $method): array
    {
        $annotations = [];

        if (PHP_VERSION_ID >= 80000) {
            $annotations = array_map(
                static fn (\ReflectionAttribute $attribute): object => $attribute->newInstance(),
                $method->getAttributes(SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF),
            );
        }

        if (null !== $this->reader) {
            $annotations = array_merge($annotations, $this->reader->getMethodAnnotations($method));
        }

        return $annotations;
    }

    /**
     * @return list<SerializerAttribute>
     */
    protected function getPropertyAnnotations(\ReflectionProperty $property): array
    {
        $annotations = [];

        if (PHP_VERSION_ID >= 80000) {
            $annotations = array_map(
                static fn (\ReflectionAttribute $attribute): object => $attribute->newInstance(),
                $property->getAttributes(SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF),
            );
        }

        if (null !== $this->reader) {
            $annotations = array_merge($annotations, $this->reader->getPropertyAnnotations($property));
        }

        return $annotations;
    }
}
