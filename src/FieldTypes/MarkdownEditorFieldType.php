<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\MarkdownEditorComponent;

/**
 * ABOUTME: Field type definition for Markdown Editor fields
 * ABOUTME: Provides Markdown Editor functionality with appropriate validation rules
 */
class MarkdownEditorFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'markdown-editor';
    }

    public function getLabel(): string
    {
        return 'Markdown Editor';
    }

    public function getIcon(): string
    {
        return 'mdi-language-markdown';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::TEXT;
    }

    public function getFormComponent(): string
    {
        return MarkdownEditorComponent::class;
    }

    public function getPriority(): int
    {
        return 85;
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
