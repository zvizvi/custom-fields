<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\ToggleButtonsComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\SingleChoiceEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\SingleChoiceColumn;

/**
 * ABOUTME: Field type definition for Toggle Buttons fields
 * ABOUTME: Provides Toggle Buttons functionality with appropriate validation rules
 */
class ToggleButtonsFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'toggle-buttons';
    }

    public function getLabel(): string
    {
        return 'Toggle Buttons';
    }

    public function getIcon(): string
    {
        return 'mdi-toggle-switch-off-outline';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::SINGLE_CHOICE;
    }

    public function getFormComponent(): string
    {
        return ToggleButtonsComponent::class;
    }

    public function getTableColumn(): string
    {
        return SingleChoiceColumn::class;
    }

    public function getInfolistEntry(): string
    {
        return SingleChoiceEntry::class;
    }

    public function getPriority(): int
    {
        return 53;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::IN,
            ValidationRule::NOT_IN,
        ];
    }
}
