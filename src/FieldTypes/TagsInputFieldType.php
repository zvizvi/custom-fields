<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes;

use Filament\Actions\Imports\ImportColumn;
use Relaticle\CustomFields\Contracts\FieldImportExportInterface;
use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\FieldTypes\Concerns\HasImportExportDefaults;
use Relaticle\CustomFields\Filament\Integration\Components\Forms\TagsInputComponent;
use Relaticle\CustomFields\Filament\Integration\Components\Infolists\MultiChoiceEntry;
use Relaticle\CustomFields\Filament\Integration\Components\Tables\Columns\MultiChoiceColumn;

/**
 * ABOUTME: Field type definition for Tags Input fields
 * ABOUTME: Provides Tags Input functionality with appropriate validation rules
 */
final class TagsInputFieldType extends BaseFieldType implements FieldImportExportInterface
{
    use HasImportExportDefaults;

    public function getKey(): string
    {
        return 'tags-input';
    }

    public function getLabel(): string
    {
        return 'Tags Input';
    }

    public function getIcon(): string
    {
        return 'mdi-tag-multiple';
    }

    public function getDataType(): FieldDataType
    {
        return FieldDataType::MULTI_CHOICE;
    }

    public function getFormComponent(): string
    {
        return TagsInputComponent::class;
    }

    public function getTableColumn(): string
    {
        return MultiChoiceColumn::class;
    }

    public function getInfolistEntry(): string
    {
        return MultiChoiceEntry::class;
    }

    public function getPriority(): int
    {
        return 70;
    }

    public function allowedValidationRules(): array
    {
        return [
            ValidationRule::REQUIRED,
            ValidationRule::ARRAY,
            ValidationRule::MIN,
            ValidationRule::MAX,
            ValidationRule::DISTINCT,
        ];
    }

    public function acceptsArbitraryValues(): bool
    {
        return true;
    }

    /**
     * Provide a custom example for tags input fields.
     */
    public function getImportExample(): string
    {
        return 'tag1, tag2, tag3';
    }

    /**
     * Configure import column to accept arbitrary values without validation.
     * Tags input should accept any values, not just predefined options.
     */
    public function configureImportColumn(ImportColumn $column): void
    {
        $column->array(separator: ',');
    }
}
