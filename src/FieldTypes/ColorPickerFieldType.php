<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\ColorPickerComponent;

/**
 * ABOUTME: Field type definition for Color Picker fields
 * ABOUTME: Provides Color Picker functionality with appropriate validation rules
 */
class ColorPickerFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'color-picker';
    }

    public function getLabel(): string
    {
        return 'Color Picker';
    }

    public function getIcon(): string
    {
        return 'mdi-palette';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::TEXT;
    }

    public function getFormComponent(): string
    {
        return ColorPickerComponent::class;
    }

    public function getPriority(): int
    {
        return 90;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::STARTS_WITH,
        ];
    }
}
