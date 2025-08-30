<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes\Configurators;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\FieldTypes\FieldTypeConfigurator;

/**
 * Configurator specific to choice fields (single and multi) with choice-specific methods
 */
class ChoiceConfigurator extends FieldTypeConfigurator
{
    public function __construct(FieldDataType $dataType)
    {
        if (! $dataType->isChoiceField()) {
            throw new \InvalidArgumentException('ChoiceConfigurator can only be used with choice field types');
        }

        parent::__construct($dataType);
    }

    /**
     * Allow users to create new options on the fly
     */
    public function allowArbitraryValues(): self
    {
        $this->withArbitraryValues();

        return $this;
    }
}
