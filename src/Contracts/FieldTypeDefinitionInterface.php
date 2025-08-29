<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Contracts;

use Closure;
use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;

/**
 * Contract for defining custom field types that can be registered dynamically.
 */
interface FieldTypeDefinitionInterface
{
    /**
     * Get the unique identifier for this field type.
     * This should be unique across all field types.
     */
    public function getKey(): string;

    /**
     * Get the human-readable label for this field type.
     */
    public function getLabel(): string;

    /**
     * Get the icon for this field type (MDI icon name).
     */
    public function getIcon(): string;

    /**
     * Get the field data type for this field type.
     */
    public function getDataType(): FieldDataType;

    /**
     * Get the form component for this field type.
     * Can return:
     * - class-string: Traditional component class approach
     * - callable: Inline component factory function
     * - null: Field type doesn't support forms
     *
     * @return class-string|Closure|null
     */
    public function getFormComponent(): string|Closure|null;

    /**
     * Get the table column for this field type.
     * Can return:
     * - class-string: Traditional column class
     * - callable: Inline column factory
     * - null: Field type doesn't support tables
     *
     * @return class-string|Closure|null
     */
    public function getTableColumn(): string|Closure|null;

    /**
     * Get the table filter for this field type.
     * Can return:
     * - class-string: Traditional filter class
     * - callable: Inline filter factory
     * - null: Field type doesn't support filtering
     *
     * @return class-string|Closure|null
     */
    public function getTableFilter(): string|Closure|null;

    /**
     * Get the infolist entry for this field type.
     * Can return:
     * - class-string: Traditional entry class
     * - callable: Inline entry factory
     * - null: Field type doesn't support infolists
     *
     * @return class-string|Closure|null
     */
    public function getInfolistEntry(): string|Closure|null;

    /**
     * Determine if this field type is searchable in tables.
     */
    public function isSearchable(): bool;

    /**
     * Determine if this field type is sortable in tables.
     */
    public function isSortable(): bool;

    /**
     * Determine if this field type is filterable in tables.
     */
    public function isFilterable(): bool;

    /**
     * Determine if this field type supports encryption.
     */
    public function isEncryptable(): bool;

    /**
     * Get the priority for field type ordering in the admin panel.
     * Lower numbers appear first.
     */
    public function getPriority(): int;

    /**
     * Get allowed validation rules for this field type.
     *
     * @return array<int, ValidationRule>
     */
    public function allowedValidationRules(): array;

    /**
     * Check if this field type accepts arbitrary values not limited to predefined options.
     * For example, tags-input allows users to create new tags on the fly.
     */
    public function acceptsArbitraryValues(): bool;
}
