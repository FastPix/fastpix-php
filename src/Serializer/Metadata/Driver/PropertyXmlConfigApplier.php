<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Metadata\Driver;

use FastPix\Sdk\Serializer\Metadata\PropertyMetadata;

/**
 * Applies the XML related options of a property's YAML configuration.
 *
 * @internal
 */
final class PropertyXmlConfigApplier
{
    /**
     * @param array $pConfig
     */
    public function apply(PropertyMetadata $pMetadata, array $pConfig): void
    {
        if (isset($pConfig['xml_list'])) {
            $pMetadata->xmlCollection = true;
            $this->applyListConfig($pMetadata, $pConfig['xml_list']);
        }

        if (isset($pConfig['xml_map'])) {
            $pMetadata->xmlCollection = true;
            $this->applyMapConfig($pMetadata, $pConfig['xml_map']);
        }

        if (isset($pConfig['xml_element'])) {
            $this->applyElementConfig($pMetadata, $pConfig['xml_element']);
        }

        if (isset($pConfig['xml_attribute'])) {
            $pMetadata->xmlAttribute = (bool) $pConfig['xml_attribute'];
        }

        if (isset($pConfig['xml_attribute_map'])) {
            $pMetadata->xmlAttributeMap = (bool) $pConfig['xml_attribute_map'];
        }

        if (isset($pConfig['xml_value'])) {
            $pMetadata->xmlValue = (bool) $pConfig['xml_value'];
        }

        if (isset($pConfig['xml_key_value_pairs'])) {
            $pMetadata->xmlKeyValuePairs = (bool) $pConfig['xml_key_value_pairs'];
        }
    }

    /**
     * @param array $colConfig
     */
    private function applyListConfig(PropertyMetadata $pMetadata, array $colConfig): void
    {
        if (isset($colConfig['inline'])) {
            $pMetadata->xmlCollectionInline = (bool) $colConfig['inline'];
        }

        if (isset($colConfig['entry_name'])) {
            $pMetadata->xmlEntryName = (string) $colConfig['entry_name'];
        }

        if (isset($colConfig['skip_when_empty'])) {
            $pMetadata->xmlCollectionSkipWhenEmpty = (bool) $colConfig['skip_when_empty'];
        } else {
            $pMetadata->xmlCollectionSkipWhenEmpty = true;
        }

        if (isset($colConfig['namespace'])) {
            $pMetadata->xmlEntryNamespace = (string) $colConfig['namespace'];
        }
    }

    /**
     * @param array $colConfig
     */
    private function applyMapConfig(PropertyMetadata $pMetadata, array $colConfig): void
    {
        if (isset($colConfig['inline'])) {
            $pMetadata->xmlCollectionInline = (bool) $colConfig['inline'];
        }

        if (isset($colConfig['entry_name'])) {
            $pMetadata->xmlEntryName = (string) $colConfig['entry_name'];
        }

        if (isset($colConfig['namespace'])) {
            $pMetadata->xmlEntryNamespace = (string) $colConfig['namespace'];
        }

        if (isset($colConfig['key_attribute_name'])) {
            $pMetadata->xmlKeyAttribute = $colConfig['key_attribute_name'];
        }
    }

    /**
     * @param array $colConfig
     */
    private function applyElementConfig(PropertyMetadata $pMetadata, array $colConfig): void
    {
        if (isset($colConfig['cdata'])) {
            $pMetadata->xmlElementCData = (bool) $colConfig['cdata'];
        }

        if (isset($colConfig['namespace'])) {
            $pMetadata->xmlNamespace = (string) $colConfig['namespace'];
        }
    }
}
