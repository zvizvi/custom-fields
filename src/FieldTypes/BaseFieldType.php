<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use BadMethodCallException;
use Closure;
use Relaticle\CustomFields\Contracts\FieldTypeDefinitionInterface;
use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\TextEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\TextColumn;

/**
 * Abstract base class for Custom Fields field types
 * Provides sensible defaults and supports both class-based and inline component definitions
 */
abstract class BaseFieldType implements FieldTypeDefinitionInterface
{
    /**
     * Required methods that each field type must implement
     */
    abstract public function getKey(): string;

    abstract public function getLabel(): string;

    abstract public function getIcon(): string;

    /**
     * Form component - can return class-string, Closure, or null
     * Override this method to define how the field appears in forms
     */
    public function getFormComponent(): string|Closure|null
    {
        throw new BadMethodCallException(
            sprintf("Field type '%s' must implement getFormComponent()", $this->getKey())
        );
    }

    /**
     * Default data type for most text-based fields
     */
    public function getDataType(): FieldDataType
    {
        return FieldDataType::TEXT;
    }

    /**
     * Table column - can return class-string, Closure, or null
     */
    public function getTableColumn(): string|Closure|null
    {
        // Default to text column for most field types
        return TextColumn::class;
    }

    /**
     * Table filter - can return class-string, Closure, or null
     */
    public function getTableFilter(): string|Closure|null
    {
        return null; // Most field types don't need custom filters
    }

    /**
     * Infolist entry - can return class-string, Closure, or null
     */
    public function getInfolistEntry(): string|Closure|null
    {
        // Default to text entry for most field types
        return TextEntry::class;
    }

    /**
     * Default priority (500 = middle priority)
     */
    public function getPriority(): int
    {
        return 500;
    }

    /**
     * Default validation rules - most fields support basic validation
     */
    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::MIN,
            ValidationRule::MAX,
        ];
    }

    /**
     * Default searchable behavior
     */
    public function isSearchable(): bool
    {
        return true;
    }

    /**
     * Default sortable behavior
     */
    public function isSortable(): bool
    {
        return true;
    }

    /**
     * Default filterable behavior
     */
    public function isFilterable(): bool
    {
        return false;
    }

    /**
     * Default encryptable behavior
     */
    public function isEncryptable(): bool
    {
        return false;
    }

    /**
     * Default arbitrary values behavior
     */
    public function acceptsArbitraryValues(): bool
    {
        return false;
    }
}
