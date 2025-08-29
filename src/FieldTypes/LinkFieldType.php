<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\LinkComponent;

/**
 * ABOUTME: Field type definition for Link fields
 * ABOUTME: Provides Link functionality with appropriate validation rules
 */
class LinkFieldType extends BaseFieldType
{
    public function getKey(): string
    {
        return 'link';
    }

    public function getLabel(): string
    {
        return 'Link';
    }

    public function getIcon(): string
    {
        return 'mdi-link';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::TEXT;
    }

    public function getFormComponent(): string
    {
        return LinkComponent::class;
    }

    public function getPriority(): int
    {
        return 60;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::URL,
            ValidationRule::STARTS_WITH,
        ];
    }
}
