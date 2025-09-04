<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldSystem;

use Closure;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Relaticle\CustomFields\Contracts\FieldTypeConfigurationInterface;

/**
 * System-wide configuration for the entire field type system
 * Provides clean, discoverable API for field type configuration and management
 */
final class SystemConfig implements FieldTypeConfigurationInterface
{
    private bool $autoDiscover = true;

    private bool $cacheEnabled = true;

    private int $cacheTtl = 3600;

    private string $cacheStore = 'default';

    private array $cacheTags = ['field-types', 'configuration'];

    private array $fieldTypes = [];

    private array $enabledFieldTypes = [];

    private array $disabledFieldTypes = [];

    private function __construct()
    {
        //
    }

    /**
     * Start building field type configuration
     */
    public static function configure(): self
    {
        return new self;
    }

    /**
     * Enable/disable automatic discovery of field types
     */
    public function discover(bool $enabled = true): self
    {
        $this->autoDiscover = $enabled;

        return $this;
    }

    /**
     * Configure caching settings
     */
    public function cache(bool $enabled = true, int $ttl = 3600, ?string $store = null, array $tags = []): self
    {
        $this->cacheEnabled = $enabled;
        $this->cacheTtl = $ttl;

        if ($store !== null) {
            $this->cacheStore = $store;
        }

        if ($tags !== []) {
            $this->cacheTags = $tags;
        }

        return $this;
    }

    /**
     * Configure field types
     */
    public function fieldTypes(array $fieldTypes): self
    {
        foreach ($fieldTypes as $fieldType) {
            if (! $fieldType instanceof FieldSettings) {
                throw new InvalidArgumentException('Field type must be an instance of FieldSettings');
            }
        }

        $this->fieldTypes = $fieldTypes;

        return $this;
    }

    /**
     * Add conditional configuration
     */
    public function when(bool $condition, Closure $callback): self
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }

    /**
     * Enable only specific field types (empty array = all enabled)
     */
    public function enabled(array $fieldTypes = []): self
    {
        $this->enabledFieldTypes = $fieldTypes;

        return $this;
    }

    /**
     * Disable specific field types
     */
    public function disabled(array $fieldTypes = []): self
    {
        $this->disabledFieldTypes = $fieldTypes;

        return $this;
    }

    /**
     * Get the field types collection (respects enabled/disabled settings)
     */
    public function getFieldTypes(): Collection
    {
        return collect($this->fieldTypes)
            ->filter(fn (FieldSettings $fieldType): bool => $this->isFieldTypeAllowed($fieldType->getKey()))
            ->keyBy(fn (FieldSettings $fieldType): string => $fieldType->getKey());
    }

    /**
     * Check if a field type is allowed based on enabled/disabled configuration
     */
    public function isFieldTypeAllowed(string $fieldTypeKey): bool
    {
        // If enabled list is specified, only allow those
        if ($this->enabledFieldTypes !== []) {
            return in_array($fieldTypeKey, $this->enabledFieldTypes);
        }

        // If disabled list is specified, exclude those
        if ($this->disabledFieldTypes !== []) {
            return ! in_array($fieldTypeKey, $this->disabledFieldTypes);
        }

        // Default: allow all field types
        return true;
    }

    /**
     * Check if caching is enabled
     */
    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    /**
     * Get cache TTL
     */
    public function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }

    /**
     * Get cache store
     */
    public function getCacheStore(): string
    {
        return $this->cacheStore;
    }

    /**
     * Get cache tags
     */
    public function getCacheTags(): array
    {
        return $this->cacheTags;
    }

    /**
     * Check if auto-discovery is enabled
     */
    public function isAutoDiscoveryEnabled(): bool
    {
        return $this->autoDiscover;
    }
}
