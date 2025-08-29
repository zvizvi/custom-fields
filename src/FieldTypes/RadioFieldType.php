<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\RadioComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\SingleChoiceEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\SingleChoiceColumn;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Filters\SelectFilter;

/**
 * ABOUTME: Field type definition for Radio fields
 * ABOUTME: Provides Radio functionality with appropriate validation rules
 */
class RadioFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'radio';
    }

    public function getLabel(): string
    {
        return 'Radio';
    }

    public function getIcon(): string
    {
        return 'mdi-radiobox-marked';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::SINGLE_CHOICE;
    }

    public function getFormComponent(): string
    {
        return RadioComponent::class;
    }

    public function getTableColumn(): string
    {
        return SingleChoiceColumn::class;
    }

    public function getTableFilter(): ?string
    {
        return SelectFilter::class;
    }

    public function getInfolistEntry(): string
    {
        return SingleChoiceEntry::class;
    }

    public function getPriority(): int
    {
        return 45;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::IN,
            ValidationRule::NOT_IN,
        ];
    }
}
