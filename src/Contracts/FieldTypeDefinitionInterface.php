<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Contracts;

use Relaticle\CustomFields\Data\FieldTypeData;
use Relaticle\CustomFields\FieldTypes\FieldTypeConfigurator;

/**
 * Contract for defining custom field types that can be registered dynamically.
 *
 * @property-read FieldTypeData $data Field type configuration data with full type hints
 *
 * @phpstan-require-extends \Relaticle\CustomFields\FieldTypes\BaseFieldType
 */
interface FieldTypeDefinitionInterface
{
    /**
     * Configure the field type capabilities and behaviors.
     * This method provides a fluent API for defining all field type characteristics.
     */
    public function configure(): FieldTypeConfigurator;
}
