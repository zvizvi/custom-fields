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

    /**
     * Get auto-discovery setting
     */
    public function getAutoDiscover(): bool;

    /**
     * Get discovery paths
     */
    public function getDiscoveryPaths(): array;

    /**
     * Get discovery namespaces
     */
    public function getDiscoveryNamespaces(): array;

    /**
     * Get excluded models
     */
    public function getExcludedModels(): array;

    /**
     * Get cache enabled setting
     */
    public function getCacheEnabled(): bool;

    /**
     * Get cache TTL
     */
    public function getCacheTtl(): int;

    /**
     * Get configured entities
     */
    public function getEntities(): array;
}
