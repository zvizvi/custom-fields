<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\DateComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\DateTimeColumn;

/**
 * ABOUTME: Field type definition for Date fields
 * ABOUTME: Provides Date functionality with appropriate validation rules
 */
class DateFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'date';
    }

    public function getLabel(): string
    {
        return 'Date';
    }

    public function getIcon(): string
    {
        return 'mdi-calendar';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::DATE;
    }

    public function getFormComponent(): string
    {
        return DateComponent::class;
    }

    public function getTableColumn(): string
    {
        return DateTimeColumn::class;
    }

    public function getPriority(): int
    {
        return 30;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::AFTER,
            ValidationRule::AFTER_OR_EQUAL,
            ValidationRule::BEFORE,
            ValidationRule::BEFORE_OR_EQUAL,
            ValidationRule::DATE_EQUALS,
        ];
    }
}
