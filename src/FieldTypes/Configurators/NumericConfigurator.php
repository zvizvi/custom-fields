<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes\Configurators;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\FieldTypes\FieldTypeConfigurator;

/**
 * Configurator specific to numeric fields with numeric-specific methods
 */
final class NumericConfigurator extends FieldTypeConfigurator
{
    public function __construct()
    {
        parent::__construct(FieldDataType::NUMERIC);
    }
}
