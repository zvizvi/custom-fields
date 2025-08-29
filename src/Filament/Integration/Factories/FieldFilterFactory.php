<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Filament\Integration\Factories;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;
use Relaticle\CustomFields\Models\CustomField;

final class FieldFilterFactory
{
    /**
     * @throws BindingResolutionException
     */
    public function create(CustomField $customField)
    {
        $tableFilterDefinition = $customField->typeData->tableFilter;
        
        if ($tableFilterDefinition === null) {
            throw new InvalidArgumentException("Field type '{$customField->type}' does not support table filters.");
        }
        
        // Handle inline component (Closure)
        if ($tableFilterDefinition instanceof Closure) {
            return $tableFilterDefinition($customField);
        } else {
            // Handle traditional component class
            $component = app($tableFilterDefinition);
            return $component->make($customField);
        }
    }
}
