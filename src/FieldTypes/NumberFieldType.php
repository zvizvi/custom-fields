<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\NumberComponent;

/**
 * ABOUTME: Field type definition for numeric input fields
 * ABOUTME: Provides number input functionality with validation for min/max values
 */
class NumberFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'number';
    }

    public function getLabel(): string
    {
        return 'Number';
    }

    public function getIcon(): string
    {
        return 'mdi-numeric';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::NUMERIC;
    }

    public function getFormComponent(): string
    {
        return NumberComponent::class;
    }

    public function getPriority(): int
    {
        return 20;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::NUMERIC,
            ValidationRule::INTEGER,
            ValidationRule::MIN,
            ValidationRule::MAX,
            ValidationRule::BETWEEN,
            ValidationRule::GT,
            ValidationRule::GTE,
            ValidationRule::LT,
            ValidationRule::LTE,
        ];
    }
}
