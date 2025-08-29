<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\TextareaFormComponent;

/**
 * ABOUTME: Field type definition for Textarea fields
 * ABOUTME: Provides Textarea functionality with appropriate validation rules
 */
final class TextareaFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'textarea';
    }

    public function getLabel(): string
    {
        return 'Textarea';
    }

    public function getIcon(): string
    {
        return 'mdi-form-textarea';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::TEXT;
    }

    public function getFormComponent(): string
    {
        return TextareaFormComponent::class;
    }

    public function getPriority(): int
    {
        return 15;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::MIN,
            ValidationRule::MAX,
        ];
    }
}
