<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\RichEditorComponent;

/**
 * ABOUTME: Field type definition for Rich Editor fields
 * ABOUTME: Provides Rich Editor functionality with appropriate validation rules
 */
final class RichEditorFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'rich-editor';
    }

    public function getLabel(): string
    {
        return 'Rich Editor';
    }

    public function getIcon(): string
    {
        return 'mdi-format-text';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::TEXT;
    }

    public function getFormComponent(): string
    {
        return RichEditorComponent::class;
    }

    public function getPriority(): int
    {
        return 80;
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
