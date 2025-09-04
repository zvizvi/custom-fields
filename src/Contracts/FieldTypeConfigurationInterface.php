<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Contracts;

use Illuminate\Support\Collection;

/**
 * Contract for field type configuration systems
 */
interface FieldTypeConfigurationInterface
{
    /**
     * Get the field types collection
     */
    public function getFieldTypes(): Collection;

    /**
     * Check if caching is enabled
     */
    public function isCacheEnabled(): bool;

    /**
     * Check if auto-discovery is enabled
     */
    public function isAutoDiscoveryEnabled(): bool;
}
