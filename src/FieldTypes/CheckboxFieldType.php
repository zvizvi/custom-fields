<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\CheckboxComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\BooleanEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\IconColumn;

/**
 * ABOUTME: Field type definition for Checkbox fields
 * ABOUTME: Provides Checkbox functionality with appropriate validation rules
 */
class CheckboxFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'checkbox';
    }

    public function getLabel(): string
    {
        return 'Checkbox';
    }

    public function getIcon(): string
    {
        return 'mdi-checkbox-marked';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::BOOLEAN;
    }

    public function getFormComponent(): string
    {
        return CheckboxComponent::class;
    }

    public function getTableColumn(): string
    {
        return IconColumn::class;
    }

    public function getInfolistEntry(): string
    {
        return BooleanEntry::class;
    }

    public function getPriority(): int
    {
        return 50;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::BOOLEAN,
            ValidationRule::ACCEPTED,
        ];
    }
}
