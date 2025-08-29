<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes\Configurators;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\FieldTypes\FieldTypeConfigurator;

/**
 * Configurator specific to boolean fields
 */
final class BooleanConfigurator extends FieldTypeConfigurator
{
    public function __construct()
    {
        parent::__construct(FieldDataType::BOOLEAN);
        // Boolean fields typically aren't searchable
        $this->searchable(false);
    }
}
