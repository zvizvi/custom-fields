<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\TextareaFormComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\TextEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\TextColumn;

/**
 * ABOUTME: Field type definition for Textarea fields
 * ABOUTME: Provides Textarea functionality with appropriate validation rules
 */
final class TextareaFieldType extends BaseFieldType
{
    public function configure(): FieldTypeConfigurator
    {
        return FieldTypeConfigurator::text()
            ->key('textarea')
            ->label('Textarea')
            ->icon('mdi-form-textarea')
            ->formComponent(TextareaFormComponent::class)
            ->tableColumn(TextColumn::class)
            ->infolistEntry(TextEntry::class)
            ->priority(15)
            ->validationRules([
                ValidationRule::REQUIRED,
                ValidationRule::MIN,
                ValidationRule::MAX,
            ]);
    }
}
