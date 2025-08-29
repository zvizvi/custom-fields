<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Closure;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\TextInputComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\TextEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\TextColumn;

/**
 * ABOUTME: Field type definition for standard text input fields
 * ABOUTME: Provides text input functionality with validation rules like min/max length
 */
class TextFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'text';
    }

    public function getLabel(): string
    {
        return 'Text';
    }

    public function getIcon(): string
    {
        return 'mdi-form-textbox';
    }

    public function getFormComponent(): string|null|Closure
    {
        return TextInputComponent::class;
    }

    public function getTableColumn(): string|null|Closure
    {
        return TextColumn::class;
    }

    public function getInfolistEntry(): string|null|Closure
    {
        return TextEntry::class;
    }

    public function getPriority(): int
    {
        return 10;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::MIN,
            ValidationRule::MAX,
            ValidationRule::ALPHA,
            ValidationRule::ALPHA_NUM,
            ValidationRule::ALPHA_DASH,
            ValidationRule::EMAIL,
            ValidationRule::STARTS_WITH,
            ValidationRule::ENDS_WITH,
        ];
    }
}
