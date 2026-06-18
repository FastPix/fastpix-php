<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata\Driver;

use FastPix\Sdk\Serializer\Annotation\ExclusionPolicy;
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
use Metadata\Driver\AbstractFileDriver;
use Metadata\Driver\AdvancedFileLocatorInterface;
use Metadata\Driver\FileLocatorInterface;
use Metadata\MethodMetadata;
use ReflectionClass;
use Symfony\Component\Yaml\Yaml;

class YamlDriver extends AbstractFileDriver
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
     * @var FileLocatorInterface
     */
    private $locator;
    /**
     * @var PropertyXmlConfigApplier
     */
    private $xmlConfigApplier;

    public function __construct(FileLocatorInterface $locator, PropertyNamingStrategyInterface $namingStrategy, ?ParserInterface $typeParser = null, ?CompilableExpressionEvaluatorInterface $expressionEvaluator = null)
    {
        $this->locator = $locator;
        $this->typeParser = $typeParser ?? new Parser();
        $this->namingStrategy = $namingStrategy;
        $this->expressionEvaluator = $expressionEvaluator;
        $this->xmlConfigApplier = new PropertyXmlConfigApplier();
    }

    public function loadMetadataForClass(ReflectionClass $class): ?BaseClassMetadata
    {
        $path = null;
        foreach ($this->getExtensions() as $extension) {
            $path = $this->locator->findFileForClass($class, $extension);
            if (null !== $path) {
                break;
            }
        }

        if (null === $path) {
            return null;
        }

        return $this->loadMetadataFromFile($class, $path);
    }

    /**
     * {@inheritDoc}
     */
    public function getAllClassNames(): array
    {
        if (!$this->locator instanceof AdvancedFileLocatorInterface) {
            throw new InvalidMetadataException(
                sprintf(
                    'Locator "%s" must be an instance of "AdvancedFileLocatorInterface".',
                    get_class($this->locator),
                ),
            );
        }

        $classes = [];
        foreach ($this->getExtensions() as $extension) {
            foreach ($this->locator->findAllClasses($extension) as $class) {
                $classes[$class] = $class;
            }
        }

        return array_values($classes);
    }

    protected function loadMetadataFromFile(ReflectionClass $class, string $file): ?BaseClassMetadata
    {
        $config = Yaml::parseFile($file, Yaml::PARSE_CONSTANT);

        if (!isset($config[$name = $class->name])) {
            throw new InvalidMetadataException(
                sprintf('Expected metadata for class %s to be defined in %s.', $class->name, $file),
            );
        }

        $config = $config[$name];
        $metadata = new ClassMetadata($name);
        $metadata->fileResources[] = $file;
        $fileResource = $class->getFilename();
        if (false !== $fileResource) {
            $metadata->fileResources[] = $fileResource;
        }

        $exclusionPolicy = isset($config['exclusion_policy']) ? strtoupper($config['exclusion_policy']) : 'NONE';
        $excludeAll = isset($config['exclude']) ? (bool) $config['exclude'] : false;

        if (isset($config['exclude_if'])) {
            $metadata->excludeIf = $this->parseExpression((string) $config['exclude_if']);
        }

        $classAccessType = $config['access_type'] ?? PropertyMetadata::ACCESS_TYPE_PROPERTY;
        $readOnlyClass = isset($config['read_only']) ? (bool) $config['read_only'] : false;
        $this->addClassProperties($metadata, $config);

        [$propertiesMetadata, $propertiesData] = $this->collectVirtualProperties($class, $name, $config);

        if (!$excludeAll) {
            $this->collectClassProperties($class, $name, $config, $propertiesMetadata, $propertiesData);
            $this->addPropertiesMetadata($metadata, $propertiesMetadata, $propertiesData, $exclusionPolicy, $classAccessType, $readOnlyClass);
        }

        $this->addCallbackMethods($metadata, $class, $config);

        return $metadata;
    }

    /**
     * @return array{0: PropertyMetadata[], 1: array<int, array|null>}
     */
    private function collectVirtualProperties(ReflectionClass $class, string $name, array $config): array
    {
        $propertiesMetadata = [];
        $propertiesData = [];
        if (!array_key_exists('virtual_properties', $config)) {
            return [$propertiesMetadata, $propertiesData];
        }

        foreach ($config['virtual_properties'] as $methodName => $propertySettings) {
            if (isset($propertySettings['exp'])) {
                $virtualPropertyMetadata = new ExpressionPropertyMetadata(
                    $name,
                    $methodName,
                    $this->parseExpression($propertySettings['exp']),
                );
                unset($propertySettings['exp']);
            } else {
                if (!$class->hasMethod($methodName)) {
                    throw new InvalidMetadataException(
                        'The method ' . $methodName . ' not found in class ' . $class->name,
                    );
                }

                $virtualPropertyMetadata = new VirtualPropertyMetadata($name, $methodName);
            }

            $propertiesMetadata[] = $virtualPropertyMetadata;
            $propertiesData[] = $propertySettings;
        }

        return [$propertiesMetadata, $propertiesData];
    }

    /**
     * @param PropertyMetadata[] $propertiesMetadata
     * @param array<int, array|null> $propertiesData
     */
    private function collectClassProperties(ReflectionClass $class, string $name, array $config, array &$propertiesMetadata, array &$propertiesData): void
    {
        foreach ($class->getProperties() as $property) {
            if ($property->class !== $name || (isset($property->info) && $property->info['class'] !== $name)) {
                continue;
            }

            $pName = $property->getName();
            $propertiesMetadata[] = new PropertyMetadata($name, $pName);
            $propertiesData[] = !empty($config['properties']) && true === array_key_exists($pName, $config['properties'])
                ? (array) $config['properties'][$pName]
                : null;
        }
    }

    /**
     * @param PropertyMetadata[] $propertiesMetadata
     * @param array<int, array|null> $propertiesData
     */
    private function addPropertiesMetadata(ClassMetadata $metadata, array $propertiesMetadata, array $propertiesData, string $exclusionPolicy, string $classAccessType, bool $readOnlyClass): void
    {
        foreach ($propertiesMetadata as $propertyKey => $pMetadata) {
            $pConfig = $propertiesData[$propertyKey];
            $isExpose = $pMetadata instanceof VirtualPropertyMetadata
                || $pMetadata instanceof ExpressionPropertyMetadata
                || isset($propertiesData[$propertyKey]);

            if (!empty($pConfig) && isset($pConfig['exclude']) && (bool) $pConfig['exclude']) {
                continue;
            }

            if (!empty($pConfig)) {
                $isExpose = $this->applyPropertyConfig($pMetadata, $pConfig, $classAccessType, $readOnlyClass, $isExpose);
            }

            $this->finalizePropertyMetadata($metadata, $pMetadata, $pConfig, $exclusionPolicy, $isExpose);
        }
    }

    /**
     * Applies a property's YAML configuration and returns whether the property should be exposed.
     *
     * @param array $pConfig
     */
    private function applyPropertyConfig(PropertyMetadata $pMetadata, array $pConfig, string $classAccessType, bool $readOnlyClass, bool $isExpose): bool
    {
        if (isset($pConfig['expose'])) {
            $isExpose = (bool) $pConfig['expose'];
        }

        $this->applyPropertyOptions($pMetadata, $pConfig);
        $this->xmlConfigApplier->apply($pMetadata, $pConfig);
        $this->applyAccessConfig($pMetadata, $pConfig, $classAccessType, $readOnlyClass);

        return $isExpose;
    }

    /**
     * @param array $pConfig
     */
    private function applyPropertyOptions(PropertyMetadata $pMetadata, array $pConfig): void
    {
        if (isset($pConfig['skip_when_empty'])) {
            $pMetadata->skipWhenEmpty = (bool) $pConfig['skip_when_empty'];
        }

        if (isset($pConfig['skip_when_null'])) {
            $pMetadata->skipWhenNull = (bool) $pConfig['skip_when_null'];
        }

        if (isset($pConfig['since_version'])) {
            $pMetadata->sinceVersion = (string) $pConfig['since_version'];
        }

        if (isset($pConfig['until_version'])) {
            $pMetadata->untilVersion = (string) $pConfig['until_version'];
        }

        if (isset($pConfig['exclude_if'])) {
            $pMetadata->excludeIf = $this->parseExpression((string) $pConfig['exclude_if']);
        }

        if (isset($pConfig['expose_if'])) {
            $pMetadata->excludeIf = $this->parseExpression('!(' . $pConfig['expose_if'] . ')');
        }

        if (isset($pConfig['serialized_name'])) {
            $pMetadata->serializedName = (string) $pConfig['serialized_name'];
        }

        if (isset($pConfig['type'])) {
            $pMetadata->setType($this->typeParser->parse((string) $pConfig['type']));
        }

        if (isset($pConfig['groups'])) {
            $pMetadata->groups = $pConfig['groups'];
        }
    }

    /**
     * @param array $pConfig
     */
    private function applyAccessConfig(PropertyMetadata $pMetadata, array $pConfig, string $classAccessType, bool $readOnlyClass): void
    {
        //we need read_only before setter and getter set, because that method depends on flag being set
        if (isset($pConfig['read_only'])) {
            $pMetadata->readOnly = (bool) $pConfig['read_only'];
        } else {
            $pMetadata->readOnly = $pMetadata->readOnly || $readOnlyClass;
        }

        $pMetadata->setAccessor(
            $pConfig['access_type'] ?? $classAccessType,
            $pConfig['accessor']['getter'] ?? null,
            $pConfig['accessor']['setter'] ?? null,
        );

        if (isset($pConfig['inline'])) {
            $pMetadata->inline = (bool) $pConfig['inline'];
        }

        if (isset($pConfig['max_depth'])) {
            $pMetadata->maxDepth = (int) $pConfig['max_depth'];
        }

        if (isset($pConfig['union_discriminator'])) {
            $pMetadata->setType([
                'name' => 'union',
                'params' => [null, $pConfig['union_discriminator']['field'], $pConfig['union_discriminator']['map']],
            ]);
        }
    }

    /**
     * @param array|null $pConfig
     */
    private function finalizePropertyMetadata(ClassMetadata $metadata, PropertyMetadata $pMetadata, $pConfig, string $exclusionPolicy, bool $isExpose): void
    {
        if (!$pMetadata->serializedName) {
            $pMetadata->serializedName = $this->namingStrategy->translateName($pMetadata);
        }

        if ($pMetadata->inline) {
            $metadata->isList = $metadata->isList || PropertyMetadata::isCollectionList($pMetadata->type);
            $metadata->isMap = $metadata->isMap || PropertyMetadata::isCollectionMap($pMetadata->type);
        }

        if (!empty($pConfig) && !empty($pConfig['name'])) {
            $pMetadata->name = (string) $pConfig['name'];
        }

        // Excluded properties never reach this point, so the policy decision only depends on the expose flag.
        if (
            ExclusionPolicy::NONE === $exclusionPolicy
            || (ExclusionPolicy::ALL === $exclusionPolicy && $isExpose)
        ) {
            $metadata->addPropertyMetadata($pMetadata);
        }
    }

    private function addCallbackMethods(ClassMetadata $metadata, ReflectionClass $class, array $config): void
    {
        if (!isset($config['callback_methods'])) {
            return;
        }

        $cConfig = $config['callback_methods'];

        if (isset($cConfig['pre_serialize'])) {
            $metadata->preSerializeMethods = $this->getCallbackMetadata($class, $cConfig['pre_serialize']);
        }

        if (isset($cConfig['post_serialize'])) {
            $metadata->postSerializeMethods = $this->getCallbackMetadata($class, $cConfig['post_serialize']);
        }

        if (isset($cConfig['post_deserialize'])) {
            $metadata->postDeserializeMethods = $this->getCallbackMetadata($class, $cConfig['post_deserialize']);
        }
    }

    /**
     * @return string[]
     */
    protected function getExtensions(): array
    {
        return array_unique([$this->getExtension(), 'yaml', 'yml']);
    }

    /**
     * @deprecated use getExtensions instead.
     */
    protected function getExtension(): string
    {
        return 'yml';
    }

    private function addClassProperties(ClassMetadata $metadata, array $config): void
    {
        if (isset($config['custom_accessor_order']) && !isset($config['accessor_order'])) {
            $config['accessor_order'] = 'custom';
        }

        if (isset($config['accessor_order'])) {
            $metadata->setAccessorOrder($config['accessor_order'], $config['custom_accessor_order'] ?? []);
        }

        if (isset($config['xml_root_name'])) {
            $metadata->xmlRootName = (string) $config['xml_root_name'];
        }

        if (isset($config['xml_root_prefix'])) {
            $metadata->xmlRootPrefix = (string) $config['xml_root_prefix'];
        }

        if (isset($config['xml_root_namespace'])) {
            $metadata->xmlRootNamespace = (string) $config['xml_root_namespace'];
        }

        if (array_key_exists('xml_namespaces', $config)) {
            foreach ($config['xml_namespaces'] as $prefix => $uri) {
                $metadata->registerNamespace($uri, $prefix);
            }
        }

        if (isset($config['discriminator'])) {
            $this->configureDiscriminator($metadata, $config['discriminator']);
        }
    }

    private function configureDiscriminator(ClassMetadata $metadata, array $discriminator): void
    {
        if (isset($discriminator['disabled']) && true === $discriminator['disabled']) {
            $metadata->discriminatorDisabled = true;

            return;
        }

        if (!isset($discriminator['field_name'])) {
            throw new InvalidMetadataException('The "field_name" attribute must be set for discriminators.');
        }

        if (!isset($discriminator['map']) || !is_array($discriminator['map'])) {
            throw new InvalidMetadataException(
                'The "map" attribute must be set, and be an array for discriminators.',
            );
        }

        $metadata->setDiscriminator(
            $discriminator['field_name'],
            $discriminator['map'],
            $discriminator['groups'] ?? [],
        );

        if (isset($discriminator['xml_attribute'])) {
            $metadata->xmlDiscriminatorAttribute = (bool) $discriminator['xml_attribute'];
        }

        if (isset($discriminator['xml_element'])) {
            $this->configureDiscriminatorXmlElement($metadata, $discriminator['xml_element']);
        }
    }

    private function configureDiscriminatorXmlElement(ClassMetadata $metadata, array $xmlElement): void
    {
        if (isset($xmlElement['cdata'])) {
            $metadata->xmlDiscriminatorCData = (bool) $xmlElement['cdata'];
        }

        if (isset($xmlElement['namespace'])) {
            $metadata->xmlDiscriminatorNamespace = (string) $xmlElement['namespace'];
        }
    }

    /**
     * @param string|string[] $config
     */
    private function getCallbackMetadata(ReflectionClass $class, $config): array
    {
        if (is_string($config)) {
            $config = [$config];
        } elseif (!is_array($config)) {
            throw new InvalidMetadataException(
                sprintf(
                    'callback methods expects a string, or an array of strings that represent method names, but got %s.',
                    json_encode($config['pre_serialize']),
                ),
            );
        }

        $methods = [];
        foreach ($config as $name) {
            if (!$class->hasMethod($name)) {
                throw new InvalidMetadataException(
                    sprintf('The method %s does not exist in class %s.', $name, $class->name),
                );
            }

            $methods[] = new MethodMetadata($class->name, $name);
        }

        return $methods;
    }
}
