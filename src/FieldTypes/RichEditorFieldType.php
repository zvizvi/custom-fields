<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\RichEditorComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\HtmlEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\TextColumn;

/**
 * ABOUTME: Field type definition for Rich Editor fields
 * ABOUTME: Provides Rich Editor functionality with appropriate validation rules
 */
final class RichEditorFieldType extends BaseFieldType
{
    public function configure(): FieldTypeConfigurator
    {
        return FieldTypeConfigurator::text()
            ->key('rich-editor')
            ->label('Rich Editor')
            ->icon('mdi-format-text')
            ->formComponent(RichEditorComponent::class)
            ->tableColumn(TextColumn::class)
            ->infolistEntry(HtmlEntry::class)
            ->priority(80)
            ->validationRules([
                ValidationRule::REQUIRED,
                ValidationRule::MIN,
                ValidationRule::MAX,
            ]);
    }
}
