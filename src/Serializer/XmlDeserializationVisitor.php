<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer;

use FastPix\Sdk\Serializer\Exception\InvalidArgumentException;
use FastPix\Sdk\Serializer\Exception\LogicException;
use FastPix\Sdk\Serializer\Exception\NotAcceptableException;
use FastPix\Sdk\Serializer\Exception\RuntimeException;
use FastPix\Sdk\Serializer\Exception\XmlErrorException;
use FastPix\Sdk\Serializer\Metadata\ClassMetadata;
use FastPix\Sdk\Serializer\Metadata\PropertyMetadata;
use FastPix\Sdk\Serializer\Visitor\DeserializationVisitorInterface;

/**
 * Visitor that turns XML into PHP objects/values during deserialization.
 *
 * The method count is high because the complex XML navigation logic has been split into
 * small, single-responsibility private helpers to keep each method's cognitive complexity
 * and number of return statements low. Re-merging them to satisfy the "too many methods"
 * rule would directly reintroduce those violations, so this rule is deliberately suppressed.
 *
 * @SuppressWarnings("php:S1448")
 */
final class XmlDeserializationVisitor extends AbstractVisitor implements NullAwareVisitorInterface, DeserializationVisitorInterface
{
    /**
     * @var \SplStack
     */
    private $objectStack;

    /**
     * @var \SplStack
     */
    private $metadataStack;

    /**
     * @var \SplStack
     */
    private $objectMetadataStack;

    /**
     * @var object|null
     */
    private $currentObject;

    /**
     * @var ClassMetadata|PropertyMetadata|null
     */
    private $currentMetadata;

    /**
     * @var bool
     */
    private $disableExternalEntities;

    /**
     * @var string[]
     */
    private $doctypeAllowList;
    /**
     * @var int
     */
    private $options;

    public function __construct(
        bool $disableExternalEntities = true,
        array $doctypeAllowList = [],
        int $options = 0
    ) {
        $this->objectStack = new \SplStack();
        $this->metadataStack = new \SplStack();
        $this->objectMetadataStack = new \SplStack();
        $this->disableExternalEntities = $disableExternalEntities;
        $this->doctypeAllowList = $doctypeAllowList;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($data)
    {
        $data = $this->emptyStringToSpaceCharacter($data);

        $previous = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $previousEntityLoaderState = null;
        if (\LIBXML_VERSION < 20900) {
            // phpcs:ignore Generic.PHP.DeprecatedFunctions.Deprecated
            $previousEntityLoaderState = libxml_disable_entity_loader($this->disableExternalEntities);
        }

        if (false !== stripos($data, '<!doctype')) {
            $internalSubset = $this->getDomDocumentTypeEntitySubset($data);
            if (!in_array($internalSubset, $this->doctypeAllowList, true)) {
                throw new InvalidArgumentException(sprintf(
                    'The document type "%s" is not allowed. If it is safe, you may add it to the allowlist configuration.',
                    $internalSubset,
                ));
            }
        }

        $doc = simplexml_load_string($data, 'SimpleXMLElement', $this->options);

        libxml_use_internal_errors($previous);

        if (\LIBXML_VERSION < 20900) {
            // phpcs:ignore Generic.PHP.DeprecatedFunctions.Deprecated
            libxml_disable_entity_loader($previousEntityLoaderState);
        }

        if (false === $doc) {
            throw new XmlErrorException(libxml_get_last_error());
        }

        return $doc;
    }

    /**
     * @param mixed $data
     */
    private function emptyStringToSpaceCharacter($data): string
    {
        return '' === $data ? ' ' : (string) $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitNull($data, array $type)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function visitString($data, array $type): string
    {
        $this->assertValueCanBeCastToString($data);

        return (string) $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitBoolean($data, array $type): bool
    {
        $this->assertValueCanBeCastToString($data);

        $data = (string) $data;

        if ('true' === $data || '1' === $data) {
            return true;
        } elseif ('false' === $data || '0' === $data) {
            return false;
        } else {
            throw new RuntimeException(sprintf('Could not convert data to boolean. Expected "true", "false", "1" or "0", but got %s.', json_encode($data)));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function visitInteger($data, array $type): int
    {
        $this->assertValueCanBeCastToInt($data);

        return (int) $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitDouble($data, array $type): float
    {
        $this->assertValueCanCastToFloat($data);

        return (float) $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray($data, array $type): array
    {
        // handle key-value-pairs
        if (null !== $this->currentMetadata && $this->currentMetadata->xmlKeyValuePairs) {
            return $this->visitKeyValuePairs($data, $type);
        }

        $entryName = null !== $this->currentMetadata && $this->currentMetadata->xmlEntryName ? $this->currentMetadata->xmlEntryName : 'entry';
        $namespace = $this->resolveEntryNamespace($data);

        if (null !== $namespace) {
            $prefix = uniqid('ns-');
            $data->registerXPathNamespace($prefix, $namespace);
            $nodes = $data->xpath(sprintf('%s:%s', $prefix, $entryName));
        } else {
            $nodes = $data->xpath($entryName);
        }

        if (null === $nodes || !\count($nodes)) {
            return [];
        }

        return $this->buildArrayResult($data, $nodes, $type, $namespace, $entryName);
    }

    /**
     * Deserializes an XML structure declared as Key-Value-Pairs (array<K,V>).
     *
     * @param mixed $data
     */
    private function visitKeyValuePairs($data, array $type): array
    {
        if (2 !== count($type['params'])) {
            throw new RuntimeException('The array type must be specified as "array<K,V>" for Key-Value-Pairs.');
        }

        $this->revertCurrentMetadata();

        [$keyType, $entryType] = $type['params'];

        $result = [];
        foreach ($data as $key => $v) {
            $k = $this->navigator->accept($key, $keyType);
            $result[$k] = $this->navigator->accept($v, $entryType);
        }

        return $result;
    }

    /**
     * Resolves the XML namespace to use for the array entries, falling back to the
     * enclosing class metadata and then the document's default namespace.
     *
     * @param mixed $data
     */
    private function resolveEntryNamespace($data): ?string
    {
        $namespace = null !== $this->currentMetadata && $this->currentMetadata->xmlEntryNamespace ? $this->currentMetadata->xmlEntryNamespace : null;

        if (null !== $namespace || !$this->objectMetadataStack->count()) {
            return $namespace;
        }

        $classMetadata = $this->objectMetadataStack->top();
        $namespace = $classMetadata->xmlNamespaces[''] ?? null;
        if (null !== $namespace) {
            return $namespace;
        }

        $namespaces = $data->getDocNamespaces();

        return $namespaces[''] ?? null;
    }

    /**
     * Builds the array result from the resolved entry nodes, dispatching to list or map handling.
     *
     * @param mixed              $data
     * @param \SimpleXMLElement[] $nodes
     */
    private function buildArrayResult($data, array $nodes, array $type, ?string $namespace, string $entryName): array
    {
        switch (\count($type['params'])) {
            case 0:
                throw new RuntimeException(sprintf('The array type must be specified either as "array<T>", or "array<K,V>".'));

            case 1:
                $result = [];

                foreach ($nodes as $v) {
                    $result[] = $this->navigator->accept($v, $type['params'][0]);
                }

                return $result;

            case 2:
                return $this->buildMapResult($data, $type, $namespace, $entryName);

            default:
                throw new LogicException(sprintf('The array type does not support more than 2 parameters, but got %s.', json_encode($type['params'])));
        }
    }

    /**
     * Builds an associative array (map) from XML entries keyed by their key attribute.
     *
     * @param mixed $data
     */
    private function buildMapResult($data, array $type, ?string $namespace, string $entryName): array
    {
        if (null === $this->currentMetadata) {
            throw new RuntimeException('Maps are not supported on top-level without metadata.');
        }

        [$keyType, $entryType] = $type['params'];
        $result = [];

        $nodes = $data->children($namespace)->$entryName;
        foreach ($nodes as $v) {
            $attrs = $v->attributes();
            if (!isset($attrs[$this->currentMetadata->xmlKeyAttribute])) {
                throw new RuntimeException(sprintf('The key attribute "%s" must be set for each entry of the map.', $this->currentMetadata->xmlKeyAttribute));
            }

            $k = $this->navigator->accept($attrs[$this->currentMetadata->xmlKeyAttribute], $keyType);
            $result[$k] = $this->navigator->accept($v, $entryType);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function visitDiscriminatorMapProperty($data, ClassMetadata $metadata): string
    {
        $value = $this->extractDiscriminatorValue($data, $metadata);

        if (null === $value) {
            throw new LogicException(sprintf(
                'The discriminator field name "%s" for base-class "%s" was not found in input data.',
                $metadata->discriminatorFieldName,
                $metadata->name,
            ));
        }

        return $value;
    }

    /**
     * Reads the discriminator value from an XML attribute or element, mirroring the
     * configured discriminator location and falling back to a plain element.
     *
     * @param mixed $data
     */
    private function extractDiscriminatorValue($data, ClassMetadata $metadata): ?string
    {
        $field = $metadata->discriminatorFieldName;
        $namespace = $metadata->xmlDiscriminatorNamespace;

        if ($metadata->xmlDiscriminatorAttribute) {
            $attributes = $data->attributes($namespace);
            if (isset($attributes[$field])) {
                return (string) $attributes[$field];
            }
        } elseif (null !== $namespace && isset($data->children($namespace)->{$field})) {
            return (string) $data->children($namespace)->{$field};
        }

        return isset($data->{$field}) ? (string) $data->{$field} : null;
    }

    public function startVisitingObject(ClassMetadata $metadata, object $object, array $type): void
    {
        $this->setCurrentObject($object);
        $this->objectMetadataStack->push($metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function visitProperty(PropertyMetadata $metadata, $data)
    {
        if (true === $metadata->inline || $metadata->xmlValue) {
            $result = $this->acceptWithType($metadata, $data);
        } elseif ($metadata->xmlAttribute) {
            $result = $this->visitAttributeProperty($metadata, $data);
        } elseif ($metadata->xmlCollection) {
            $result = $this->visitCollectionProperty($metadata, $data);
        } else {
            $result = $this->visitElementProperty($metadata, $data);
        }

        return $result;
    }

    /**
     * Accepts the given node using the property's declared type, ensuring metadata exists.
     *
     * @param mixed $node
     *
     * @return mixed
     */
    private function acceptWithType(PropertyMetadata $metadata, $node)
    {
        if (!$metadata->type) {
            throw RuntimeException::noMetadataForProperty($metadata->class, $metadata->name);
        }

        return $this->navigator->accept($node, $metadata->type);
    }

    /**
     * Reads a property mapped to an XML attribute.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function visitAttributeProperty(PropertyMetadata $metadata, $data)
    {
        $attributes = $data->attributes($metadata->xmlNamespace);
        if (!isset($attributes[$metadata->serializedName])) {
            throw new NotAcceptableException();
        }

        return $this->acceptWithType($metadata, $attributes[$metadata->serializedName]);
    }

    /**
     * Reads a property mapped to an XML collection, tracking the metadata for the entries.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function visitCollectionProperty(PropertyMetadata $metadata, $data)
    {
        $enclosingElem = $data;
        if (!$metadata->xmlCollectionInline) {
            $enclosingElem = $data->children($metadata->xmlNamespace)->{$metadata->serializedName};
        }

        $this->setCurrentMetadata($metadata);
        if (!$metadata->type) {
            throw RuntimeException::noMetadataForProperty($metadata->class, $metadata->name);
        }

        $v = $this->navigator->accept($enclosingElem, $metadata->type);
        $this->revertCurrentMetadata();

        return $v;
    }

    /**
     * Reads a property mapped to an XML element, resolving the node and handling key-value-pairs.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function visitElementProperty(PropertyMetadata $metadata, $data)
    {
        $node = $this->resolvePropertyNode($metadata, $data);

        if ($metadata->xmlKeyValuePairs) {
            $this->setCurrentMetadata($metadata);
        }

        return $this->acceptWithType($metadata, $node);
    }

    /**
     * Locates the XML node for an element property, taking namespaces into account.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function resolvePropertyNode(PropertyMetadata $metadata, $data)
    {
        $name = $metadata->serializedName;

        if ($metadata->xmlNamespace) {
            $node = $data->children($metadata->xmlNamespace)->$name;
            if (!$node->count()) {
                throw new NotAcceptableException();
            }

            return $node;
        }

        if ('' === $metadata->xmlNamespace) {
            // See #1087 - element must be like: <element xmlns="" /> - https://www.w3.org/TR/REC-xml-names/#iri-use
            // Use of an empty string in a namespace declaration turns it into an "undeclaration".
            $nodes = $data->xpath('./' . $name);
            if (empty($nodes)) {
                throw new NotAcceptableException();
            }

            return reset($nodes);
        }

        $namespaces = $data->getDocNamespaces();
        if (isset($namespaces[''])) {
            $prefix = uniqid('ns-');
            $data->registerXPathNamespace($prefix, $namespaces['']);
            $nodes = $data->xpath('./' . $prefix . ':' . $name);
        } else {
            $nodes = $data->xpath('./' . $name);
        }

        if (empty($nodes)) {
            throw new NotAcceptableException();
        }

        return reset($nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function endVisitingObject(ClassMetadata $metadata, $data, array $type): object
    {
        $rs = $this->currentObject;
        $this->objectMetadataStack->pop();
        $this->revertCurrentObject();

        return $rs;
    }

    public function setCurrentObject(object $object): void
    {
        $this->objectStack->push($this->currentObject);
        $this->currentObject = $object;
    }

    public function getCurrentObject(): ?object
    {
        return $this->currentObject;
    }

    public function revertCurrentObject(): ?object
    {
        return $this->currentObject = $this->objectStack->pop();
    }

    public function setCurrentMetadata(PropertyMetadata $metadata): void
    {
        $this->metadataStack->push($this->currentMetadata);
        $this->currentMetadata = $metadata;
    }

    /**
     * @return ClassMetadata|PropertyMetadata|null
     */
    public function getCurrentMetadata()
    {
        return $this->currentMetadata;
    }

    /**
     * @return ClassMetadata|PropertyMetadata|null
     */
    public function revertCurrentMetadata()
    {
        return $this->currentMetadata = $this->metadataStack->pop();
    }

    /**
     * {@inheritdoc}
     */
    public function getResult($data)
    {
        unset($this->navigator);

        return $data;
    }

    /**
     * Retrieves internalSubset even in bugfixed php versions
     */
    private function getDomDocumentTypeEntitySubset(string $data): string
    {
        $startPos = $endPos = stripos($data, '<!doctype');
        $braces = 0;
        do {
            $char = $data[$endPos++];
            if ('<' === $char) {
                ++$braces;
            }

            if ('>' === $char) {
                --$braces;
            }
        } while ($braces > 0);

        $internalSubset = substr($data, $startPos, $endPos - $startPos);
        $internalSubset = str_replace(["\n", "\r"], '', $internalSubset);
        $internalSubset = preg_replace('/\s{2,}/', ' ', $internalSubset);
        $internalSubset = str_replace(['[ <!', '> ]>'], ['[<!', '>]>'], $internalSubset);

        return $internalSubset;
    }

    /**
     * {@inheritdoc}
     */
    public function isNull($value): bool
    {
        if ($value instanceof \SimpleXMLElement) {
            // Workaround for https://bugs.php.net/bug.php?id=75168 and https://github.com/schmittjoh/serializer/issues/817
            // If the "name" is empty means that we are on an not-existent node and subsequent operations on the object will trigger the warning:
            // "Node no longer exists"
            if ('' === $value->getName()) {
                // NOTE: this returns false (treating the node as non-null) on purpose. Returning
                // true here would be more correct for scalars, but collections would then lack a
                // default collection value; the stricter behaviour is deferred to a future major version.
                return false;
            }

            $xsiAttributes = $value->attributes('http://www.w3.org/2001/XMLSchema-instance');
            if (
                isset($xsiAttributes['nil'])
                && ('true' === (string) $xsiAttributes['nil'] || '1' === (string) $xsiAttributes['nil'])
            ) {
                return true;
            }
        }

        return null === $value;
    }
}
