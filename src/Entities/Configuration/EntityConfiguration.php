<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Entities\Configuration;

use Closure;
use InvalidArgumentException;
use Relaticle\CustomFields\Entities\Configuration\Contracts\EntityConfigurationInterface;

/**
 * Fluent builder for configuring the entire entity management system
 * Provides clean, discoverable API for global entity configuration
 */
final class EntityConfiguration implements EntityConfigurationInterface
{
    private bool $autoDiscover = true;

    private array $discoveryPaths = [];

    private array $discoveryNamespaces = [];

    private array $excludedModels = [];

    private bool $cacheEnabled = true;

    private int $cacheTtl = 3600;

    private array $entityModels = [];

    private function __construct()
    {
        // Set smart defaults
        $this->discoveryPaths = [app_path('Models')];
        $this->discoveryNamespaces = ['App\\Models'];
    }

    /**
     * Start building entity configuration
     */
    public static function configure(): self
    {
        return new self;
    }

    /**
     * Enable/disable automatic discovery of entities
     */
    public function autoDiscover(bool $enabled = true): self
    {
        $this->autoDiscover = $enabled;

        return $this;
    }

    /**
     * Set paths to discover entities from
     */
    public function discover(string|array $paths): self
    {
        $this->discoveryPaths = is_array($paths) ? $paths : [$paths];

        return $this;
    }

    /**
     * Set namespaces to discover entities from
     */
    public function namespaces(array $namespaces): self
    {
        $this->discoveryNamespaces = $namespaces;

        return $this;
    }

    /**
     * Only include specific models (disables auto-discovery of others)
     */
    public function include(array $models): self
    {
        // When include is used, disable auto-discovery and only use specified models
        $this->autoDiscover = false;
        $this->excludedModels = [];

        // Convert model classes to EntityModel instances if needed
        $entityModels = [];
        foreach ($models as $model) {
            if (is_string($model) && class_exists($model)) {
                $entityModels[] = EntityModel::for($model);
            } elseif ($model instanceof EntityModel) {
                $entityModels[] = $model;
            }
        }

        return $this->models($entityModels);
    }

    /**
     * Exclude specific models from discovery and configuration
     */
    public function exclude(array $models): self
    {
        $this->excludedModels = $models;

        return $this;
    }

    /**
     * Configure caching for entity discovery
     */
    public function cache(bool $enabled = true, int $ttl = 3600): self
    {
        $this->cacheEnabled = $enabled;
        $this->cacheTtl = max(60, $ttl); // Minimum 1 minute cache

        return $this;
    }

    /**
     * Register specific entity models with custom configuration
     */
    public function models(array $entityModels): self
    {
        foreach ($entityModels as $entityModel) {
            if (! $entityModel instanceof EntityModel) {
                throw new InvalidArgumentException('All models must be instances of EntityModel');
            }
        }

        $this->entityModels = $entityModels;

        return $this;
    }

    /**
     * Register entities using a closure for deferred execution
     */
    public function modelsUsing(Closure $callback): self
    {
        $models = $callback();

        if (! is_array($models)) {
            throw new InvalidArgumentException('Callback must return an array of EntityModel instances');
        }

        return $this->models($models);
    }

    /**
     * Build the final configuration array compatible with the system
     */
    public function build(): array
    {
        return [
            'auto_discover_entities' => $this->autoDiscover,
            'entity_discovery_paths' => $this->discoveryPaths,
            'entity_discovery_namespaces' => $this->discoveryNamespaces,
            'excluded_models' => $this->excludedModels,
            'cache_entities' => $this->cacheEnabled,
            'cache_ttl' => $this->cacheTtl,
            'entities' => $this->buildEntitiesArray(),
        ];
    }

    /**
     * Build the entities array from configured EntityModel instances
     */
    private function buildEntitiesArray(): array
    {
        $entities = [];

        foreach ($this->entityModels as $entityModel) {
            $config = $entityModel->build();
            $entities[$config->getAlias()] = [
                'modelClass' => $config->getModelClass(),
                'alias' => $config->getAlias(),
                'labelSingular' => $config->getLabelSingular(),
                'labelPlural' => $config->getLabelPlural(),
                'icon' => $config->getIcon(),
                'primaryAttribute' => $config->getPrimaryAttribute(),
                'searchAttributes' => $config->getSearchAttributes(),
                'resourceClass' => $config->getResourceClass(),
                'features' => $config->getFeatures(),
                'priority' => $config->getPriority(),
                'metadata' => $config->getMetadata(),
            ];
        }

        return $entities;
    }

    /**
     * Get a simple configuration for common use cases
     */
    public static function simple(): self
    {
        return self::configure()
            ->autoDiscover(true)
            ->cache(true);
    }

    /**
     * Get a configuration with auto-discovery disabled
     */
    public static function manual(): self
    {
        return self::configure()
            ->autoDiscover(false)
            ->cache(true);
    }

    /**
     * Get a configuration optimized for development
     */
    public static function development(): self
    {
        return self::configure()
            ->autoDiscover(true)
            ->cache(false); // No caching in development for immediate updates
    }

    /**
     * Get a configuration optimized for production
     */
    public static function production(): self
    {
        return self::configure()
            ->autoDiscover(true)
            ->cache(true, 7200); // 2 hour cache for production
    }
}
