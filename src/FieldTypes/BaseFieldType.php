<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Contracts\FieldTypeDefinitionInterface;

/**
 * Abstract base class for Custom Fields field types
 * Provides sensible defaults and supports both class-based and inline component definitions
 */
abstract class BaseFieldType implements FieldTypeDefinitionInterface
{
    abstract public function configure(): FieldTypeConfigurator;

    /**
     * Get the built-in options for this field type.
     * Only called when providesBuiltInOptions() returns true.
     *
     * @return array<string, string>
     */
    public function getBuiltInOptions(): array
    {
        return [];
    }
}
