<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes\Configurators;

use Relaticle\CustomFields\Enums\FieldDataType;

/**
 * Configurator specific to multi-choice fields (checkbox list, multi-select, tags, etc.)
 */
final class MultiChoiceConfigurator extends ChoiceConfigurator
{
    public function __construct()
    {
        parent::__construct(FieldDataType::MULTI_CHOICE);
        // Multi choice fields are typically filterable
        $this->filterable();
    }
}
