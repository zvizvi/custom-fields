<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\LinkComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\TextEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\TextColumn;

/**
 * ABOUTME: Field type definition for Link fields
 * ABOUTME: Provides Link functionality with appropriate validation rules
 */
class LinkFieldType extends BaseFieldType
{
    public function configure(): FieldTypeConfigurator
    {
        return FieldTypeConfigurator::text()
            ->key('link')
            ->label('Link')
            ->icon('mdi-link')
            ->formComponent(LinkComponent::class)
            ->tableColumn(TextColumn::class)
            ->infolistEntry(TextEntry::class)
            ->priority(60)
            ->validationRules([
                ValidationRule::REQUIRED,
                ValidationRule::URL,
                ValidationRule::STARTS_WITH,
            ]);
    }
}
