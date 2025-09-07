<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Contracts;

/**
 * Contract for entity configuration builders
 * Ensures consistent interface for all configuration approaches
 */
interface EntityConfigurationInterface
{
    /**
     * Build the final configuration array
     */
    public static function configure(): self;
}
