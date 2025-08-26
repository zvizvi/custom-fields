<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Entities\Configuration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Relaticle\CustomFields\Data\EntityConfigurationData;
use Relaticle\CustomFields\Enums\EntityFeature;

/**
 * Fluent builder for configuring individual entity models
 * Provides type-safe, discoverable API for entity configuration
 */
final class EntityModel
{
    private string $modelClass;

    private ?string $alias = null;

    private ?string $labelSingular = null;

    private ?string $labelPlural = null;

    private string $icon = 'heroicon-o-document';

    private string $primaryAttribute = 'id';

    private array $searchAttributes = [];

    private ?string $resourceClass = null;

    private Collection $features;

    private int $priority = 999;

    private array $metadata = [];

    private function __construct(string $modelClass)
    {
        $this->validateModelClass($modelClass);
        $this->modelClass = $modelClass;
        $this->features = collect([EntityFeature::CUSTOM_FIELDS, EntityFeature::LOOKUP_SOURCE]);
        $this->setSmartDefaults();
    }

    /**
     * Create a new entity model configuration
     */
    public static function for(string $modelClass): self
    {
        return new self($modelClass);
    }

    /**
     * Set the entity alias (defaults to model morph class)
     */
    public function alias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Set the display labels for this entity
     */
    public function label(string $singular, ?string $plural = null): self
    {
        $this->labelSingular = $singular;
        $this->labelPlural = $plural ?? Str::plural($singular);

        return $this;
    }

    /**
     * Set the icon for this entity
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the primary attribute for display purposes
     */
    public function primaryAttribute(string $attribute): self
    {
        $this->primaryAttribute = $attribute;

        return $this;
    }

    /**
     * Set the attributes to search within
     */
    public function searchIn(array $attributes): self
    {
        $this->searchAttributes = $attributes;

        return $this;
    }

    /**
     * Set the available features for this entity
     */
    public function features(array $features): self
    {
        $this->features = collect($features)->map(function ($feature) {
            if (is_string($feature)) {
                return EntityFeature::from($feature);
            }

            if ($feature instanceof EntityFeature) {
                return $feature;
            }

            throw new InvalidArgumentException('Features must be EntityFeature enum values or their string representations');
        });

        return $this;
    }

    /**
     * Set the priority for this entity (lower numbers = higher priority)
     */
    public function priority(int $priority): self
    {
        $this->priority = max(0, $priority);

        return $this;
    }

    /**
     * Set additional metadata for this entity
     */
    public function metadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Set the associated Filament Resource class
     */
    public function resource(string $resourceClass): self
    {
        if (! class_exists($resourceClass)) {
            throw new InvalidArgumentException(sprintf('Resource class %s does not exist', $resourceClass));
        }

        $this->resourceClass = $resourceClass;

        return $this;
    }

    /**
     * Build the final EntityConfigurationData object
     */
    public function build(): EntityConfigurationData
    {
        return EntityConfigurationData::from([
            'modelClass' => $this->modelClass,
            'alias' => $this->alias,
            'labelSingular' => $this->labelSingular,
            'labelPlural' => $this->labelPlural,
            'icon' => $this->icon,
            'primaryAttribute' => $this->primaryAttribute,
            'searchAttributes' => $this->searchAttributes,
            'resourceClass' => $this->resourceClass,
            'features' => $this->features,
            'priority' => $this->priority,
            'metadata' => $this->metadata,
        ]);
    }

    /**
     * Validate the model class is valid
     */
    private function validateModelClass(string $modelClass): void
    {
        if (! class_exists($modelClass)) {
            throw new InvalidArgumentException(sprintf('Model class %s does not exist', $modelClass));
        }

        if (! is_subclass_of($modelClass, Model::class)) {
            throw new InvalidArgumentException(sprintf('Class %s must extend ', $modelClass).Model::class);
        }
    }

    /**
     * Set smart defaults based on the model class
     */
    private function setSmartDefaults(): void
    {
        /** @var Model $model */
        $model = new $this->modelClass;

        // Set default alias to model's morph class
        $this->alias = $model->getMorphClass();

        // Set default labels based on class name
        $baseName = class_basename($this->modelClass);
        $this->labelSingular = Str::headline($baseName);
        $this->labelPlural = Str::plural($this->labelSingular);

        // Set smart primary attribute
        $this->primaryAttribute = $this->guessePrimaryAttribute($model);

        // Set smart search attributes
        $this->searchAttributes = $this->guessSearchAttributes($model);
    }

    /**
     * Guess the best primary attribute for this model
     */
    private function guessePrimaryAttribute(Model $model): string
    {
        $fillable = $model->getFillable();

        // Check common title attributes
        foreach (['name', 'title', 'label', 'display_name'] as $attribute) {
            if (in_array($attribute, $fillable, true)) {
                return $attribute;
            }
        }

        return 'id';
    }

    /**
     * Guess the best search attributes for this model
     */
    private function guessSearchAttributes(Model $model): array
    {
        $primary = $this->guessePrimaryAttribute($model);

        // If we found a good primary attribute, use it for search
        if ($primary !== 'id') {
            return [$primary];
        }

        return [];
    }
}
