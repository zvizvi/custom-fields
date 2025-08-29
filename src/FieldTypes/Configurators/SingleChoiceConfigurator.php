<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes\Configurators;

use Relaticle\CustomFields\Enums\FieldDataType;

/**
 * Configurator specific to single choice fields (select, radio, etc.)
 */
final class SingleChoiceConfigurator extends ChoiceConfigurator
{
    public function __construct()
    {
        parent::__construct(FieldDataType::SINGLE_CHOICE);
        // Single choice fields are typically filterable
        $this->filterable();
    }
}
