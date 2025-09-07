<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\FieldTypeSystem;

use Closure;
use Relaticle\CustomFields\Enums\ValidationRule;

/**
 * Individual field type settings and customization
 * Provides type-safe, discoverable API for field type configuration
 */
final class FieldSettings
{
    private string $key;

    private ?string $label = null;

    private ?string $icon = null;

    private ?int $priority = null;

    private array $defaultValidationRules = [];

    private array $availableValidationRules = [];

    private array $settings = [];

    private array $roles = [];

    private array $permissions = [];

    private array $metadata = [];

    private function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Create a new field type configuration
     */
    public static function for(string $key): self
    {
        return new self($key);
    }

    /**
     * Set the display label for this field type
     */
    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the icon for this field type
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the priority for field type ordering
     */
    public function priority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Set default validation rules that are always applied
     */
    public function defaultValidationRules(array $rules): self
    {
        $this->defaultValidationRules = array_map(
            fn ($rule) => $rule instanceof ValidationRule ? $rule->value : $rule,
            $rules
        );

        return $this;
    }

    /**
     * Set available validation rules for user selection
     */
    public function availableValidationRules(array $rules): self
    {
        $this->availableValidationRules = array_map(
            fn ($rule) => $rule instanceof ValidationRule ? $rule->value : $rule,
            $rules
        );

        return $this;
    }

    /**
     * Set field type specific settings
     */
    public function settings(array $settings): self
    {
        $this->settings = array_merge($this->settings, $settings);

        return $this;
    }

    /**
     * Set roles that can use this field type
     */
    public function roles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set permissions required to use this field type
     */
    public function permissions(array $permissions): self
    {
        $this->permissions = $permissions;

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
     * Add metadata to this field type
     */
    public function metadata(array $metadata): self
    {
        $this->metadata = array_merge($this->metadata, $metadata);

        return $this;
    }

    // Getters

    /**
     * Get the field type key
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the display label
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Get the icon
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Get the priority
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * Get default validation rules
     */
    public function getDefaultValidationRules(): array
    {
        return $this->defaultValidationRules;
    }

    /**
     * Get available validation rules
     */
    public function getAvailableValidationRules(): array
    {
        return $this->availableValidationRules;
    }

    /**
     * Get settings
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * Get roles
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Get permissions
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Get metadata
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Convert to array for storage/caching
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'icon' => $this->icon,
            'priority' => $this->priority,
            'default_validation_rules' => $this->defaultValidationRules,
            'available_validation_rules' => $this->availableValidationRules,
            'settings' => $this->settings,
            'roles' => $this->roles,
            'permissions' => $this->permissions,
            'metadata' => $this->metadata,
        ];
    }
}
