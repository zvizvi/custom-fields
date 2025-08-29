<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

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
    public function configure(): FieldTypeConfigurator
    {
        return FieldTypeConfigurator::boolean()
            ->key('checkbox')
            ->label('Checkbox')
            ->icon('mdi-checkbox-marked')
            ->formComponent(CheckboxComponent::class)
            ->tableColumn(IconColumn::class)
            ->infolistEntry(BooleanEntry::class)
            ->priority(50)
            ->validationRules([
                ValidationRule::REQUIRED,
                ValidationRule::BOOLEAN,
                ValidationRule::ACCEPTED,
            ]);
    }
}
