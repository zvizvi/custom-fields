<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypeSystem\Definitions;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\FieldTypeSystem\BaseFieldType;
use Relaticle\CustomFields\FieldTypeSystem\FieldSchema;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\PhoneComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\TextEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\TextColumn;

/**
 * ABOUTME: Field type definition for phone number input fields
 * ABOUTME: Provides specialized phone input with formatting and international support
 */
class PhoneFieldType extends BaseFieldType
{
    public function configure(): FieldSchema
    {
        return FieldSchema::string()
            ->key('phone')
            ->label('Phone Number')
            ->icon('heroicon-o-phone')
            ->formComponent(PhoneComponent::class)
            ->tableColumn(TextColumn::class)
            ->infolistEntry(TextEntry::class)
            ->priority(16)
            ->encryptable()
            ->searchable()
            ->sortable()
            ->availableValidationRules([
                ValidationRule::REQUIRED,
                ValidationRule::MIN,
                ValidationRule::MAX,
                ValidationRule::REGEX,
                ValidationRule::STARTS_WITH,
                ValidationRule::UNIQUE,
                ValidationRule::EXISTS,
            ]);
    }
}
