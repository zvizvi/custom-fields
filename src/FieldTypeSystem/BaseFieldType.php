<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypeSystem;

use InvalidArgumentException;
use Relaticle\CustomFields\Contracts\FieldTypeDefinitionInterface;
use Relaticle\CustomFields\Data\FieldTypeData;

/**
 * Abstract base class for Custom Fields field types
 * Provides sensible defaults and supports both class-based and inline component definitions
 *
 * @property-read FieldTypeData $data Field type configuration data with full type hints
 */
abstract class BaseFieldType implements FieldTypeDefinitionInterface
{
    private ?FieldTypeData $_data = null;

    abstract public function configure(): FieldSchema;

    /**
     * Get field type data with proper type hints and caching
     */
    public function getData(): FieldTypeData
    {
        if (! $this->_data instanceof FieldTypeData) {
            $this->_data = $this->configure()->data();
        }

        return $this->_data;
    }

    /**
     * Magic getter for clean property access: $fieldType->data
     */
    public function __get(string $property): mixed
    {
        if ($property === 'data') {
            return $this->getData();
        }

        throw new InvalidArgumentException(sprintf('Property %s does not exist', $property));
    }
}
