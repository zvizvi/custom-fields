<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\CheckboxListComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\MultiChoiceEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\MultiChoiceColumn;

/**
 * ABOUTME: Field type definition for Checkbox List fields
 * ABOUTME: Provides Checkbox List functionality with appropriate validation rules
 */
class CheckboxListFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'checkbox-list';
    }

    public function getLabel(): string
    {
        return 'Checkbox List';
    }

    public function getIcon(): string
    {
        return 'mdi-checkbox-multiple-marked';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::MULTI_CHOICE;
    }

    public function getFormComponent(): string
    {
        return CheckboxListComponent::class;
    }

    public function getTableColumn(): string
    {
        return MultiChoiceColumn::class;
    }

    public function getInfolistEntry(): string
    {
        return MultiChoiceEntry::class;
    }

    public function getPriority(): int
    {
        return 55;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::ARRAY,
            ValidationRule::MIN,
            ValidationRule::MAX,
            ValidationRule::DISTINCT,
        ];
    }
}
