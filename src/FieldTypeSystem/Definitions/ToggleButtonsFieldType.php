<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypeSystem\Definitions;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\FieldTypeSystem\BaseFieldType;
use Relaticle\CustomFields\FieldTypeSystem\FieldSchema;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\ToggleButtonsComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\SingleChoiceEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\SingleChoiceColumn;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Filters\SelectFilter;

/**
 * ABOUTME: Field type definition for Toggle Buttons fields
 * ABOUTME: Provides Toggle Buttons functionality with appropriate validation rules
 */
class ToggleButtonsFieldType extends BaseFieldType
{
    public function configure(): FieldSchema
    {
        return FieldSchema::singleChoice()
            ->key('toggle-buttons')
            ->label('Toggle Buttons')
            ->icon('mdi-toggle-switch-off-outline')
            ->formComponent(ToggleButtonsComponent::class)
            ->tableColumn(SingleChoiceColumn::class)
            ->tableFilter(SelectFilter::class)
            ->infolistEntry(SingleChoiceEntry::class)
            ->priority(53)
            ->availableValidationRules([
                ValidationRule::REQUIRED,
                ValidationRule::IN,
                ValidationRule::NOT_IN,
            ])
            ->filterable();
    }
}
