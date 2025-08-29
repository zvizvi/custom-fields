<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Closure;
use Relaticle\CustomFields\Data\FieldTypeData;
use Relaticle\CustomFields\Enums\FieldDataType;

/**
 * Fluent configurator for field type capabilities and behaviors.
 * Provides a chainable API for configuring field type features.
 */
class FieldTypeConfigurator
{
    private FieldDataType $dataType;

    // Field identity
    private string $key = '';

    private string $label = '';

    private string $icon = '';

    // Component definitions
    private string|Closure|null $formComponent = null;

    private string|Closure|null $tableColumn = null;

    private string|Closure|null $tableFilter = null;

    private string|Closure|null $infolistEntry = null;

    // Field properties
    private int $priority = 500;

    private array $validationRules = [];

    // Capabilities
    private bool $searchable = true;

    private bool $sortable = true;

    private bool $filterable = false;

    private bool $encryptable = false;

    private bool $acceptsArbitraryValues = false;

    private bool $providesBuiltInOptions = false;

    public function __construct(FieldDataType $dataType)
    {
        $this->dataType = $dataType;
    }

    /**
     * Create a configurator for a specific data type.
     */
    public static function for(FieldDataType $dataType): self
    {
        return new self($dataType);
    }

    // ========== Data Type Specific Factory Methods ==========

    /**
     * Configure for text-based fields (STRING, TEXT)
     */
    public static function text(): Configurators\TextConfigurator
    {
        return new Configurators\TextConfigurator;
    }

    /**
     * Configure for string fields (shorter text)
     */
    public static function string(): Configurators\TextConfigurator
    {
        return new Configurators\TextConfigurator;
    }

    /**
     * Configure for numeric fields
     */
    public static function numeric(): Configurators\NumericConfigurator
    {
        return new Configurators\NumericConfigurator;
    }

    /**
     * Configure for float fields
     */
    public static function float(): self
    {
        return new self(FieldDataType::FLOAT);
    }

    /**
     * Configure for date fields
     */
    public static function date(): self
    {
        return new self(FieldDataType::DATE);
    }

    /**
     * Configure for datetime fields
     */
    public static function dateTime(): self
    {
        return new self(FieldDataType::DATE_TIME);
    }

    /**
     * Configure for boolean fields
     */
    public static function boolean(): Configurators\BooleanConfigurator
    {
        return new Configurators\BooleanConfigurator;
    }

    /**
     * Configure for single choice fields (select, radio, etc.)
     */
    public static function singleChoice(): Configurators\SingleChoiceConfigurator
    {
        return new Configurators\SingleChoiceConfigurator;
    }

    /**
     * Configure for multi-choice fields (checkboxes, multi-select, etc.)
     */
    public static function multiChoice(): Configurators\MultiChoiceConfigurator
    {
        return new Configurators\MultiChoiceConfigurator;
    }

    // ========== Field Identity Configuration Methods ==========

    /**
     * Set the field key
     */
    public function key(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set the field label
     */
    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the field icon
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    // ========== Component Configuration Methods ==========

    /**
     * Set the form component for this field type
     */
    public function formComponent(string|Closure $component): self
    {
        $this->formComponent = $component;

        return $this;
    }

    /**
     * Set the table column for this field type
     */
    public function tableColumn(string|Closure $column): self
    {
        $this->tableColumn = $column;

        return $this;
    }

    /**
     * Set the table filter for this field type
     */
    public function tableFilter(string|Closure $filter): self
    {
        $this->tableFilter = $filter;

        return $this;
    }

    /**
     * Set the infolist entry for this field type
     */
    public function infolistEntry(string|Closure $entry): self
    {
        $this->infolistEntry = $entry;

        return $this;
    }

    /**
     * Set the priority for field ordering
     */
    public function priority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Set the validation rules for this field type
     */
    public function validationRules(array $rules): self
    {
        $this->validationRules = $rules;

        return $this;
    }

    // ========== Common Capability Methods ==========

    /**
     * Configure searchability in tables
     */
    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Configure sortability in tables
     */
    public function sortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Configure filterability in tables
     */
    public function filterable(bool $filterable = true): self
    {
        $this->filterable = $filterable;

        return $this;
    }

    /**
     * Configure encryption capability
     */
    public function encryptable(bool $encryptable = true): self
    {
        $this->encryptable = $encryptable;

        return $this;
    }

    /**
     * Configure whether field accepts arbitrary values (like tags input)
     */
    public function withArbitraryValues(bool $accepts = true): self
    {
        $this->acceptsArbitraryValues = $accepts;

        return $this;
    }

    /**
     * Configure whether field provides built-in options (like predefined colors)
     */
    public function withBuiltInOptions(bool $provides = true): self
    {
        $this->providesBuiltInOptions = $provides;

        return $this;
    }

    // ========== Export Configuration ==========

    /**
     * Get the field key
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the field label
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get the field icon
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Get the data type for this configuration
     */
    public function getDataType(): FieldDataType
    {
        return $this->dataType;
    }

    /**
     * Get the form component
     */
    public function getFormComponent(): string|Closure|null
    {
        return $this->formComponent;
    }

    /**
     * Get the table column
     */
    public function getTableColumn(): string|Closure|null
    {
        return $this->tableColumn;
    }

    /**
     * Get the table filter
     */
    public function getTableFilter(): string|Closure|null
    {
        return $this->tableFilter;
    }

    /**
     * Get the infolist entry
     */
    public function getInfolistEntry(): string|Closure|null
    {
        return $this->infolistEntry;
    }

    /**
     * Get the priority
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Get the validation rules
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    /**
     * Check if field is searchable
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * Check if field is sortable
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * Check if field is filterable
     */
    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    /**
     * Check if field is encryptable
     */
    public function isEncryptable(): bool
    {
        return $this->encryptable;
    }

    /**
     * Check if field accepts arbitrary values
     */
    public function acceptsArbitraryValues(): bool
    {
        return $this->acceptsArbitraryValues;
    }

    /**
     * Check if field provides built-in options
     */
    public function providesBuiltInOptions(): bool
    {
        return $this->providesBuiltInOptions;
    }

    public function data(): FieldTypeData
    {
        return new FieldTypeData(
            key: $this->key,
            label: $this->label,
            icon: $this->icon,
            priority: $this->priority,
            dataType: $this->dataType,
            tableColumn: $this->tableColumn,
            tableFilter: $this->tableFilter,
            formComponent: $this->formComponent,
            infolistEntry: $this->infolistEntry,
            searchable: $this->searchable,
            sortable: $this->sortable,
            filterable: $this->filterable,
            encryptable: $this->encryptable,
            acceptsArbitraryValues: $this->acceptsArbitraryValues,
            providesBuiltInOptions: $this->providesBuiltInOptions,
            validationRules: $this->validationRules
        );
    }
}
