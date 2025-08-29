<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Filament\Integration\Factories;

use Closure;
use Filament\Tables\Columns\Column;
use InvalidArgumentException;
use Relaticle\CustomFields\Models\CustomField;
use Relaticle\CustomFields\Support\Utils;

final class FieldColumnFactory
{
    public function create(CustomField $customField): Column
    {
        $tableColumnDefinition = $customField->typeData->tableColumn;
        
        if ($tableColumnDefinition === null) {
            throw new InvalidArgumentException("Field type '{$customField->type}' does not support table columns.");
        }
        
        // Handle inline component (Closure)
        if ($tableColumnDefinition instanceof Closure) {
            $column = $tableColumnDefinition($customField);
        } else {
            // Handle traditional component class
            $component = app($tableColumnDefinition);
            $column = $component->make($customField);
        }

        return $column
            ->toggleable(
                condition: Utils::isTableColumnsToggleableEnabled(),
                isToggledHiddenByDefault: $customField->settings->list_toggleable_hidden
            )
            ->columnSpan($customField->width->getSpanValue());
    }
}
