<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypes\Configurators;

use Relaticle\CustomFields\Enums\FieldDataType;
use Relaticle\CustomFields\FieldTypes\FieldTypeConfigurator;

/**
 * Configurator specific to text fields with text-specific methods
 */
final class TextConfigurator extends FieldTypeConfigurator
{
    public function __construct()
    {
        parent::__construct(FieldDataType::TEXT);
    }

    /**
     * Enable encryption for this text field
     */
    public function encrypted(): self
    {
        $this->encryptable();

        return $this;
    }

    /**
     * Configure as a long text field (textarea)
     */
    public function longText(): self
    {
        // Could modify internal configuration for textarea vs input
        return $this;
    }
}
