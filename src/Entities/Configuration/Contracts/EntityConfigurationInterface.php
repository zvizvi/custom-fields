<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Entities\Configuration\Contracts;

/**
 * Contract for entity configuration builders
 * Ensures consistent interface for all configuration approaches
 */
interface EntityConfigurationInterface
{
    /**
     * Build the final configuration array
     */
    public function build(): array;
}
