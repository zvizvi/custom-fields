<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Contracts;

use Relaticle\CustomFields\FieldTypes\FieldTypeConfigurator;

/**
 * Contract for defining custom field types that can be registered dynamically.
 */
interface FieldTypeDefinitionInterface
{
    /**
     * Configure the field type capabilities and behaviors.
     * This method provides a fluent API for defining all field type characteristics.
     */
    public function configure(): FieldTypeConfigurator;
}
