<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\SelectComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\SingleChoiceEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\SingleChoiceColumn;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Filters\SelectFilter;

class SelectFieldType extends BaseFieldType
{
    public function configure(): FieldTypeConfigurator
    {
        return FieldTypeConfigurator::singleChoice()
            ->key('select')
            ->label('Select')
            ->icon('mdi-form-select')
            ->formComponent(SelectComponent::class)
            ->tableColumn(SingleChoiceColumn::class)
            ->tableFilter(SelectFilter::class)
            ->infolistEntry(SingleChoiceEntry::class)
            ->priority(50)
            ->validationRules([
                ValidationRule::REQUIRED,
                ValidationRule::IN,
                ValidationRule::NOT_IN,
            ])
            ->filterable();
    }
}
