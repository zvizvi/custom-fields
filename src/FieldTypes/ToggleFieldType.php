<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\ToggleComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\BooleanEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\IconColumn;

/**
 * ABOUTME: Field type definition for Toggle fields
 * ABOUTME: Provides Toggle functionality with appropriate validation rules
 */
class ToggleFieldType extends BaseFieldType
{
    public function configure(): FieldTypeConfigurator
    {
        return FieldTypeConfigurator::boolean()
            ->key('toggle')
            ->label('Toggle')
            ->icon('mdi-toggle-switch')
            ->formComponent(ToggleComponent::class)
            ->tableColumn(IconColumn::class)
            ->infolistEntry(BooleanEntry::class)
            ->priority(52)
            ->validationRules([
                ValidationRule::REQUIRED,
                ValidationRule::BOOLEAN,
                ValidationRule::ACCEPTED,
            ]);
    }
}
