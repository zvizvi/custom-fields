<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\MultiSelectComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\MultiChoiceEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\MultiChoiceColumn;

/**
 * ABOUTME: Field type definition for Multi Select fields
 * ABOUTME: Provides Multi Select functionality with appropriate validation rules
 */
class MultiSelectFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'multi-select';
    }

    public function getLabel(): string
    {
        return 'Multi Select';
    }

    public function getIcon(): string
    {
        return 'mdi-form-dropdown';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::MULTI_CHOICE;
    }

    public function getFormComponent(): string
    {
        return MultiSelectComponent::class;
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
        return 42;
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
