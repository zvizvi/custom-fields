<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldSystem\Definitions;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\FieldSystem\BaseFieldType;
use Relaticle\CustomFields\FieldSystem\FieldSchema;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\DateComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\DateTimeEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\DateTimeColumn;

/**
 * ABOUTME: Field type definition for Date fields
 * ABOUTME: Provides Date functionality with appropriate validation rules
 */
class DateFieldType extends BaseFieldType
{
    public function configure(): FieldSchema
    {
        return FieldSchema::date()
            ->key('date')
            ->label('Date')
            ->icon('mdi-calendar')
            ->formComponent(DateComponent::class)
            ->tableColumn(DateTimeColumn::class)
            ->infolistEntry(DateTimeEntry::class)
            ->priority(30)
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
