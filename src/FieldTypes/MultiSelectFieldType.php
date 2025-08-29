<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\MultiSelectComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\MultiChoiceEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\MultiChoiceColumn;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Filters\SelectFilter;

/**
 * ABOUTME: Field type definition for Multi Select fields
 * ABOUTME: Provides Multi Select functionality with appropriate validation rules
 */
class MultiSelectFieldType extends BaseFieldType
{
    public function configure(): FieldTypeConfigurator
    {
        return FieldTypeConfigurator::multiChoice()
            ->key('multi-select')
            ->label('Multi Select')
            ->icon('mdi-form-dropdown')
            ->formComponent(MultiSelectComponent::class)
            ->tableColumn(MultiChoiceColumn::class)
            ->tableFilter(SelectFilter::class)
            ->infolistEntry(MultiChoiceEntry::class)
            ->priority(42)
            ->validationRules([
                ValidationRule::REQUIRED,
                ValidationRule::ARRAY,
                ValidationRule::MIN,
                ValidationRule::MAX,
                ValidationRule::DISTINCT,
            ])
            ->filterable();
    }
}
