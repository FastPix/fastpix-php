<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata\Driver;

use FastPix\Sdk\Serializer\Annotation\ExclusionPolicy;
use FastPix\Sdk\Serializer\Exception\InvalidMetadataException;
use FastPix\Sdk\Serializer\Exception\XmlErrorException;
use FastPix\Sdk\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use FastPix\Sdk\Serializer\Metadata\ClassMetadata;
use FastPix\Sdk\Serializer\Metadata\ExpressionPropertyMetadata;
use FastPix\Sdk\Serializer\Metadata\PropertyMetadata;
use FastPix\Sdk\Serializer\Metadata\VirtualPropertyMetadata;
use FastPix\Sdk\Serializer\Naming\PropertyNamingStrategyInterface;
use FastPix\Sdk\Serializer\Type\Parser;
use FastPix\Sdk\Serializer\Type\ParserInterface;
use Metadata\ClassMetadata as BaseClassMetadata;
use Metadata\Driver\AbstractFileDriver;
use Metadata\Driver\FileLocatorInterface;
use Metadata\MethodMetadata;

/**
 * @method ClassMetadata|null loadMetadataForClass(\ReflectionClass $class)
 */
class XmlDriver extends AbstractFileDriver
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

    public function __construct(FileLocatorInterface $locator, PropertyNamingStrategyInterface $namingStrategy, ?ParserInterface $typeParser = null, ?CompilableExpressionEvaluatorInterface $expressionEvaluator = null)
    {
        parent::__construct($locator);

        $this->typeParser = $typeParser ?? new Parser();
        $this->namingStrategy = $namingStrategy;
        $this->expressionEvaluator = $expressionEvaluator;
    }

    protected function loadMetadataFromFile(\ReflectionClass $class, string $path): ?BaseClassMetadata
    {
        $name = $class->name;
        $elem = $this->parseClassElement($path, $name);

        $metadata = new ClassMetadata($name);
        $metadata->fileResources[] = $path;
        $fileResource = $class->getFilename();
        if (false !== $fileResource) {
            $metadata->fileResources[] = $fileResource;
        }

        $config = $this->loadClassAttributes($elem, $metadata);
        $this->loadDiscriminator($elem, $metadata);
        $this->loadXmlNamespaces($elem, $metadata);
        $this->loadXmlDiscriminator($elem, $metadata);

        $propertiesMetadata = [];
        $propertiesNodes = [];
        $this->loadVirtualProperties($elem, $name, $propertiesMetadata, $propertiesNodes);

        if (!$config['excludeAll']) {
            $this->collectXmlProperties($class, $name, $elem, $propertiesMetadata, $propertiesNodes);

            foreach ($propertiesMetadata as $propertyKey => $pMetadata) {
                $this->configureXmlProperty($pMetadata, $propertiesNodes[$propertyKey], $metadata, $config);
            }
        }

        $this->loadCallbackMethods($elem, $class, $metadata);

        return $metadata;
    }

    /**
     * Loads and validates the XML file, returning the <class> element for $name.
     */
    private function parseClassElement(string $path, string $name): \SimpleXMLElement
    {
        $previous = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $elem = simplexml_load_file($path);
        libxml_use_internal_errors($previous);

        if (false === $elem) {
            throw new InvalidMetadataException('Invalid XML content for metadata', 0, new XmlErrorException(libxml_get_last_error()));
        }

        $elems = $elem->xpath("./class[@name = '" . $name . "']");
        if (!$elems) {
            throw new InvalidMetadataException(sprintf('Could not find class %s inside XML element.', $name));
        }

        return reset($elems);
    }

    /**
     * Reads class-level attributes into the metadata and returns class-level config.
     *
     * @return array{exclusionPolicy: string, excludeAll: bool, classAccessType: string, readOnlyClass: bool}
     */
    private function loadClassAttributes(\SimpleXMLElement $elem, ClassMetadata $metadata): array
    {
        $attributes = $elem->attributes();
        $exclude = $attributes->exclude;

        $config = [
            'exclusionPolicy' => strtoupper((string) $attributes->{'exclusion-policy'}) ?: 'NONE',
            'excludeAll' => null !== $exclude ? 'true' === strtolower((string) $exclude) : false,
            'classAccessType' => (string) ($attributes->{'access-type'} ?: PropertyMetadata::ACCESS_TYPE_PROPERTY),
            'readOnlyClass' => 'true' === strtolower((string) $attributes->{'read-only'}),
        ];

        if (null !== $excludeIf = $attributes->{'exclude-if'}) {
            $metadata->excludeIf = $this->parseExpression((string) $excludeIf);
        }

        if (null !== $accessorOrder = $attributes->{'accessor-order'}) {
            $metadata->setAccessorOrder((string) $accessorOrder, preg_split('/\s*,\s*/', (string) $attributes->{'custom-accessor-order'}));
        }

        if (null !== $xmlRootName = $attributes->{'xml-root-name'}) {
            $metadata->xmlRootName = (string) $xmlRootName;
        }

        if (null !== $xmlRootNamespace = $attributes->{'xml-root-namespace'}) {
            $metadata->xmlRootNamespace = (string) $xmlRootNamespace;
        }

        if (null !== $xmlRootPrefix = $attributes->{'xml-root-prefix'}) {
            $metadata->xmlRootPrefix = (string) $xmlRootPrefix;
        }

        return $config;
    }

    private function loadDiscriminator(\SimpleXMLElement $elem, ClassMetadata $metadata): void
    {
        $discriminatorFieldName = (string) $elem->attributes()->{'discriminator-field-name'};
        $discriminatorMap = [];
        foreach ($elem->xpath('./discriminator-class') as $entry) {
            if (!isset($entry->attributes()->value)) {
                throw new InvalidMetadataException('Each discriminator-class element must have a "value" attribute.');
            }

            $discriminatorMap[(string) $entry->attributes()->value] = (string) $entry;
        }

        if ('true' === (string) $elem->attributes()->{'discriminator-disabled'}) {
            $metadata->discriminatorDisabled = true;
        } elseif (!empty($discriminatorFieldName) || !empty($discriminatorMap)) {
            $discriminatorGroups = [];
            foreach ($elem->xpath('./discriminator-groups/group') as $entry) {
                $discriminatorGroups[] = (string) $entry;
            }

            $metadata->setDiscriminator($discriminatorFieldName, $discriminatorMap, $discriminatorGroups);
        }
    }

    private function loadXmlNamespaces(\SimpleXMLElement $elem, ClassMetadata $metadata): void
    {
        foreach ($elem->xpath('./xml-namespace') as $xmlNamespace) {
            if (!isset($xmlNamespace->attributes()->uri)) {
                throw new InvalidMetadataException('The prefix attribute must be set for all xml-namespace elements.');
            }

            $prefix = isset($xmlNamespace->attributes()->prefix)
                ? (string) $xmlNamespace->attributes()->prefix
                : null;

            $metadata->registerNamespace((string) $xmlNamespace->attributes()->uri, $prefix);
        }
    }

    private function loadXmlDiscriminator(\SimpleXMLElement $elem, ClassMetadata $metadata): void
    {
        foreach ($elem->xpath('./xml-discriminator') as $xmlDiscriminator) {
            if (isset($xmlDiscriminator->attributes()->attribute)) {
                $metadata->xmlDiscriminatorAttribute = 'true' === (string) $xmlDiscriminator->attributes()->attribute;
            }

            if (isset($xmlDiscriminator->attributes()->cdata)) {
                $metadata->xmlDiscriminatorCData = 'true' === (string) $xmlDiscriminator->attributes()->cdata;
            }

            if (isset($xmlDiscriminator->attributes()->namespace)) {
                $metadata->xmlDiscriminatorNamespace = (string) $xmlDiscriminator->attributes()->namespace;
            }
        }
    }

    /**
     * @param PropertyMetadata[]      $propertiesMetadata
     * @param array<\SimpleXMLElement> $propertiesNodes
     */
    private function loadVirtualProperties(\SimpleXMLElement $elem, string $name, array &$propertiesMetadata, array &$propertiesNodes): void
    {
        foreach ($elem->xpath('./virtual-property') as $method) {
            if (isset($method->attributes()->expression)) {
                $virtualPropertyMetadata = new ExpressionPropertyMetadata(
                    $name,
                    (string) $method->attributes()->name,
                    $this->parseExpression((string) $method->attributes()->expression),
                );
            } else {
                if (!isset($method->attributes()->method)) {
                    throw new InvalidMetadataException('The method attribute must be set for all virtual-property elements.');
                }

                $virtualPropertyMetadata = new VirtualPropertyMetadata($name, (string) $method->attributes()->method);
            }

            $propertiesMetadata[] = $virtualPropertyMetadata;
            $propertiesNodes[] = $method;
        }
    }

    /**
     * @param PropertyMetadata[]            $propertiesMetadata
     * @param array<\SimpleXMLElement|null> $propertiesNodes
     */
    private function collectXmlProperties(\ReflectionClass $class, string $name, \SimpleXMLElement $elem, array &$propertiesMetadata, array &$propertiesNodes): void
    {
        foreach ($class->getProperties() as $property) {
            if ($property->class !== $name || (isset($property->info) && $property->info['class'] !== $name)) {
                continue;
            }

            $pName = $property->getName();
            $propertiesMetadata[] = new PropertyMetadata($name, $pName);

            $pElems = $elem->xpath("./property[@name = '" . $pName . "']");
            $propertiesNodes[] = $pElems ? reset($pElems) : null;
        }
    }

    /**
     * @param \SimpleXMLElement|null $pElem
     * @param array{exclusionPolicy: string, excludeAll: bool, classAccessType: string, readOnlyClass: bool} $config
     */
    private function configureXmlProperty(PropertyMetadata $pMetadata, $pElem, ClassMetadata $metadata, array $config): void
    {
        $isExpose = $pMetadata instanceof VirtualPropertyMetadata
            || $pMetadata instanceof ExpressionPropertyMetadata
            || null !== $pElem;

        if (!empty($pElem)) {
            $exclude = $pElem->attributes()->exclude;
            if (null !== $exclude && 'true' === strtolower((string) $exclude)) {
                return;
            }

            $this->applyXmlExposure($pMetadata, $pElem, $isExpose);
            $this->applyXmlVersioningAndType($pMetadata, $pElem);
            $this->applyXmlListConfig($pMetadata, $pElem);
            $this->applyXmlMapConfig($pMetadata, $pElem);
            $this->applyXmlElementConfig($pMetadata, $pElem);
            // read-only must be applied before the accessor, which depends on the flag.
            $this->applyXmlScalarFlags($pMetadata, $pElem, $config['readOnlyClass']);
            $this->applyXmlAccessor($pMetadata, $pElem, $config['classAccessType']);
        }

        if ($pMetadata->inline) {
            $metadata->isList = $metadata->isList || PropertyMetadata::isCollectionList($pMetadata->type);
            $metadata->isMap = $metadata->isMap || PropertyMetadata::isCollectionMap($pMetadata->type);
        }

        if (!$pMetadata->serializedName) {
            $pMetadata->serializedName = $this->namingStrategy->translateName($pMetadata);
        }

        if (!empty($pElem) && null !== $nameAttr = $pElem->attributes()->name) {
            $pMetadata->name = (string) $nameAttr;
        }

        // When reached, the property is never excluded (an excluded one returns early above),
        // so the ExclusionPolicy::NONE branch no longer needs the !isExclude guard.
        if (
            ExclusionPolicy::NONE === (string) $config['exclusionPolicy']
            || (ExclusionPolicy::ALL === (string) $config['exclusionPolicy'] && $isExpose)
        ) {
            $metadata->addPropertyMetadata($pMetadata);
        }
    }

    private function applyXmlExposure(PropertyMetadata $pMetadata, \SimpleXMLElement $pElem, bool &$isExpose): void
    {
        $attributes = $pElem->attributes();

        if (null !== $expose = $attributes->expose) {
            $isExpose = 'true' === strtolower((string) $expose);
        }

        if (null !== $excludeIf = $attributes->{'exclude-if'}) {
            $pMetadata->excludeIf = $this->parseExpression((string) $excludeIf);
        }

        if (null !== $skipEmpty = $attributes->{'skip-when-empty'}) {
            $pMetadata->skipWhenEmpty = 'true' === strtolower((string) $skipEmpty);
        }

        if (null !== $skipNull = $attributes->{'skip-when-null'}) {
            $pMetadata->skipWhenNull = 'true' === strtolower((string) $skipNull);
        }

        if (null !== $excludeIf = $attributes->{'expose-if'}) {
            $pMetadata->excludeIf = $this->parseExpression('!(' . (string) $excludeIf . ')');
            $isExpose = true;
        }
    }

    private function applyXmlVersioningAndType(PropertyMetadata $pMetadata, \SimpleXMLElement $pElem): void
    {
        $attributes = $pElem->attributes();

        if (null !== $version = $attributes->{'since-version'}) {
            $pMetadata->sinceVersion = (string) $version;
        }

        if (null !== $version = $attributes->{'until-version'}) {
            $pMetadata->untilVersion = (string) $version;
        }

        if (null !== $serializedName = $attributes->{'serialized-name'}) {
            $pMetadata->serializedName = (string) $serializedName;
        }

        if (null !== $type = $attributes->type) {
            $pMetadata->setType($this->typeParser->parse((string) $type));
        } elseif (isset($pElem->type)) {
            $pMetadata->setType($this->typeParser->parse((string) $pElem->type));
        }

        if (null !== $groups = $attributes->groups) {
            $pMetadata->groups = preg_split('/\s*,\s*/', trim((string) $groups));
        } elseif (isset($pElem->groups)) {
            $pMetadata->groups = (array) $pElem->groups->value;
        }
    }

    private function applyXmlListConfig(PropertyMetadata $pMetadata, \SimpleXMLElement $pElem): void
    {
        if (!isset($pElem->{'xml-list'})) {
            return;
        }

        $pMetadata->xmlCollection = true;
        $colConfig = $pElem->{'xml-list'};

        if (isset($colConfig->attributes()->inline)) {
            $pMetadata->xmlCollectionInline = 'true' === (string) $colConfig->attributes()->inline;
        }

        if (isset($colConfig->attributes()->{'entry-name'})) {
            $pMetadata->xmlEntryName = (string) $colConfig->attributes()->{'entry-name'};
        }

        if (isset($colConfig->attributes()->{'skip-when-empty'})) {
            $pMetadata->xmlCollectionSkipWhenEmpty = 'true' === (string) $colConfig->attributes()->{'skip-when-empty'};
        } else {
            $pMetadata->xmlCollectionSkipWhenEmpty = true;
        }

        if (isset($colConfig->attributes()->namespace)) {
            $pMetadata->xmlEntryNamespace = (string) $colConfig->attributes()->namespace;
        }
    }

    private function applyXmlMapConfig(PropertyMetadata $pMetadata, \SimpleXMLElement $pElem): void
    {
        if (!isset($pElem->{'xml-map'})) {
            return;
        }

        $pMetadata->xmlCollection = true;
        $colConfig = $pElem->{'xml-map'};

        if (isset($colConfig->attributes()->inline)) {
            $pMetadata->xmlCollectionInline = 'true' === (string) $colConfig->attributes()->inline;
        }

        if (isset($colConfig->attributes()->{'entry-name'})) {
            $pMetadata->xmlEntryName = (string) $colConfig->attributes()->{'entry-name'};
        }

        if (isset($colConfig->attributes()->namespace)) {
            $pMetadata->xmlEntryNamespace = (string) $colConfig->attributes()->namespace;
        }

        if (isset($colConfig->attributes()->{'key-attribute-name'})) {
            $pMetadata->xmlKeyAttribute = (string) $colConfig->attributes()->{'key-attribute-name'};
        }
    }

    private function applyXmlElementConfig(PropertyMetadata $pMetadata, \SimpleXMLElement $pElem): void
    {
        if (!isset($pElem->{'xml-element'})) {
            return;
        }

        $colConfig = $pElem->{'xml-element'};

        if (isset($colConfig->attributes()->cdata)) {
            $pMetadata->xmlElementCData = 'true' === (string) $colConfig->attributes()->cdata;
        }

        if (isset($colConfig->attributes()->namespace)) {
            $pMetadata->xmlNamespace = (string) $colConfig->attributes()->namespace;
        }
    }

    private function applyXmlScalarFlags(PropertyMetadata $pMetadata, \SimpleXMLElement $pElem, bool $readOnlyClass): void
    {
        $attributes = $pElem->attributes();

        if (isset($attributes->{'xml-attribute'})) {
            $pMetadata->xmlAttribute = 'true' === (string) $attributes->{'xml-attribute'};
        }

        if (isset($attributes->{'xml-attribute-map'})) {
            $pMetadata->xmlAttributeMap = 'true' === (string) $attributes->{'xml-attribute-map'};
        }

        if (isset($attributes->{'xml-value'})) {
            $pMetadata->xmlValue = 'true' === (string) $attributes->{'xml-value'};
        }

        if (isset($attributes->{'xml-key-value-pairs'})) {
            $pMetadata->xmlKeyValuePairs = 'true' === (string) $attributes->{'xml-key-value-pairs'};
        }

        if (isset($attributes->{'max-depth'})) {
            $pMetadata->maxDepth = (int) $attributes->{'max-depth'};
        }

        // we need read-only before setter and getter set, because that method depends on flag being set
        if (null !== $readOnly = $attributes->{'read-only'}) {
            $pMetadata->readOnly = 'true' === strtolower((string) $readOnly);
        } else {
            $pMetadata->readOnly = $pMetadata->readOnly || $readOnlyClass;
        }
    }

    private function applyXmlAccessor(PropertyMetadata $pMetadata, \SimpleXMLElement $pElem, string $classAccessType): void
    {
        if (isset($pElem->{'union-discriminator'})) {
            $colConfig = $pElem->{'union-discriminator'};

            $map = [];
            foreach ($pElem->xpath('./union-discriminator/map/class') as $entry) {
                $map[(string) $entry->attributes()->key] = (string) $entry;
            }

            $pMetadata->setType([
                'name' => 'union',
                'params' => [null, (string) $colConfig->attributes()->field, $map],
            ]);
        }

        $getter = $pElem->attributes()->{'accessor-getter'};
        $setter = $pElem->attributes()->{'accessor-setter'};
        $pMetadata->setAccessor(
            (string) ($pElem->attributes()->{'access-type'} ?: $classAccessType),
            $getter ? (string) $getter : null,
            $setter ? (string) $setter : null,
        );

        if (null !== $inline = $pElem->attributes()->inline) {
            $pMetadata->inline = 'true' === strtolower((string) $inline);
        }
    }

    private function loadCallbackMethods(\SimpleXMLElement $elem, \ReflectionClass $class, ClassMetadata $metadata): void
    {
        foreach ($elem->xpath('./callback-method') as $method) {
            if (!isset($method->attributes()->type)) {
                throw new InvalidMetadataException('The type attribute must be set for all callback-method elements.');
            }

            if (!isset($method->attributes()->name)) {
                throw new InvalidMetadataException('The name attribute must be set for all callback-method elements.');
            }

            switch ((string) $method->attributes()->type) {
                case 'pre-serialize':
                    $metadata->addPreSerializeMethod(new MethodMetadata($class->name, (string) $method->attributes()->name));
                    break;

                case 'post-serialize':
                    $metadata->addPostSerializeMethod(new MethodMetadata($class->name, (string) $method->attributes()->name));
                    break;

                case 'post-deserialize':
                    $metadata->addPostDeserializeMethod(new MethodMetadata($class->name, (string) $method->attributes()->name));
                    break;

                case 'handler':
                    if (!isset($method->attributes()->format)) {
                        throw new InvalidMetadataException('The format attribute must be set for "handler" callback methods.');
                    }

                    if (!isset($method->attributes()->direction)) {
                        throw new InvalidMetadataException('The direction attribute must be set for "handler" callback methods.');
                    }

                    break;

                default:
                    throw new InvalidMetadataException(sprintf('The type "%s" is not supported.', $method->attributes()->name));
            }
        }
    }

    protected function getExtension(): string
    {
        return 'xml';
    }
}
