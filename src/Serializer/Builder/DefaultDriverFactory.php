<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Builder;

use FastPix\Sdk\Serializer\Exception\RuntimeException;
use FastPix\Sdk\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use FastPix\Sdk\Serializer\Metadata\Driver\AnnotationOrAttributeDriver;
use FastPix\Sdk\Serializer\Metadata\Driver\DefaultValuePropertyDriver;
use FastPix\Sdk\Serializer\Metadata\Driver\EnumPropertiesDriver;
use FastPix\Sdk\Serializer\Metadata\Driver\NullDriver;
use FastPix\Sdk\Serializer\Metadata\Driver\TypedPropertiesDriver;
use FastPix\Sdk\Serializer\Metadata\Driver\XmlDriver;
use FastPix\Sdk\Serializer\Metadata\Driver\YamlDriver;
use FastPix\Sdk\Serializer\Naming\PropertyNamingStrategyInterface;
use FastPix\Sdk\Serializer\Type\Parser;
use FastPix\Sdk\Serializer\Type\ParserInterface;
use Metadata\Driver\DriverChain;
use Metadata\Driver\DriverInterface;
use Metadata\Driver\FileLocator;

final class DefaultDriverFactory implements DriverFactoryInterface
{
    /**
     * @var ParserInterface
     */
    private $typeParser;

    /**
     * @var bool
     */
    private $enableEnumSupport = false;

    /**
     * @var PropertyNamingStrategyInterface
     */
    private $propertyNamingStrategy;

    /**
     * @var CompilableExpressionEvaluatorInterface
     */
    private $expressionEvaluator;

    public function __construct(PropertyNamingStrategyInterface $propertyNamingStrategy, ?ParserInterface $typeParser = null, ?CompilableExpressionEvaluatorInterface $expressionEvaluator = null)
    {
        $this->typeParser = $typeParser ?: new Parser();
        $this->propertyNamingStrategy = $propertyNamingStrategy;
        $this->expressionEvaluator = $expressionEvaluator;
    }

    public function enableEnumSupport(bool $enableEnumSupport = true): void
    {
        $this->enableEnumSupport = $enableEnumSupport;
    }

    /**
     * @param array<string, string> $metadataDirs
     * @param object|null $annotationReader Doctrine\Common\Annotations\Reader when doctrine/annotations is installed (optional; PHP 8+ uses attributes)
     */
    public function createDriver(array $metadataDirs, ?object $annotationReader = null): DriverInterface
    {
        $readerInterface = 'Doctrine\Common\Annotations\Reader';
        if (PHP_VERSION_ID < 80000 && empty($metadataDirs) && !interface_exists($readerInterface)) {
            throw new RuntimeException(sprintf('To use "%s", either a list of metadata directories must be provided, the "doctrine/annotations" package installed, or use PHP 8.0 or later.', self::class));
        }

        /*
         * Build the sorted list of metadata drivers based on the environment. The final order should be:
         *
         * - YAML Driver
         * - XML Driver
         * - Annotations/Attributes Driver
         * - Null (Fallback) Driver
         */
        $metadataDrivers = [];

        if (PHP_VERSION_ID >= 80000 || ($annotationReader !== null && interface_exists($readerInterface) && $annotationReader instanceof \Doctrine\Common\Annotations\Reader)) {
            $metadataDrivers[] = new AnnotationOrAttributeDriver($this->propertyNamingStrategy, $this->typeParser, $this->expressionEvaluator, $annotationReader);
        }

        if (!empty($metadataDirs)) {
            $fileLocator = new FileLocator($metadataDirs);

            array_unshift($metadataDrivers, new XmlDriver($fileLocator, $this->propertyNamingStrategy, $this->typeParser, $this->expressionEvaluator));

            if (class_exists(\Symfony\Component\Yaml\Yaml::class)) {
                array_unshift($metadataDrivers, new YamlDriver($fileLocator, $this->propertyNamingStrategy, $this->typeParser, $this->expressionEvaluator));
            }
        }

        $driver = new DriverChain($metadataDrivers);
        $driver->addDriver(new NullDriver($this->propertyNamingStrategy));

        if ($this->enableEnumSupport) {
            $driver = new EnumPropertiesDriver($driver);
        }

        $driver = new TypedPropertiesDriver($driver, $this->typeParser);

        if (PHP_VERSION_ID >= 80000) {
            $driver = new DefaultValuePropertyDriver($driver);
        }

        return $driver;
    }
}
