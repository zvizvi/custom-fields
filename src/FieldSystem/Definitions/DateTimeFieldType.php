<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldSystem\Definitions;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\FieldSystem\BaseFieldType;
use Relaticle\CustomFields\FieldSystem\FieldSchema;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\DateTimeComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\DateTimeEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\DateTimeColumn;

class DateTimeFieldType extends BaseFieldType
{
    public function configure(): FieldSchema
    {
        return FieldSchema::dateTime()
            ->key('date-time')
            ->label('Date and Time')
            ->icon('mdi-calendar-clock')
            ->formComponent(DateTimeComponent::class)
            ->tableColumn(DateTimeColumn::class)
            ->infolistEntry(DateTimeEntry::class)
            ->priority(35)
            ->availableValidationRules([
                ValidationRule::REQUIRED,
                ValidationRule::AFTER,
                ValidationRule::AFTER_OR_EQUAL,
                ValidationRule::BEFORE,
                ValidationRule::BEFORE_OR_EQUAL,
                ValidationRule::DATE_EQUALS,
            ]);
    }
}
