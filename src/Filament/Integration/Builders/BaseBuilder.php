<?php

// ABOUTME: Base builder class that provides common functionality for building Filament components
// ABOUTME: Handles model binding, field filtering (except/only), and section grouping

namespace Relaticle\CustomFields\Filament\Integration\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Relaticle\CustomFields\CustomFields;
use Relaticle\CustomFields\Models\Contracts\HasCustomFields;
use Relaticle\CustomFields\Models\CustomFieldSection;

abstract class BaseBuilder
{
    protected Model&HasCustomFields $model;

    protected Builder $sections;

    protected array $except = [];

    protected array $only = [];

    public function forModel(Model|string $model): static
    {
        if (is_string($model)) {
            $model = app($model);
        }

        if (! $model instanceof HasCustomFields) {
            throw new InvalidArgumentException('Model must implement HasCustomFields interface.');
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException('Model must be an Eloquent Model.');
        }

        if(!self::class instanceof TableBuilder) {
            $model->load('customFieldValues.customField');
        }

        $this->model = $model;

        $this->sections = CustomFields::newSectionModel()->query()
            ->forEntityType($model::class)
            ->orderBy('sort_order');

        return $this;
    }

    public function except(array $fieldCodes): static
    {
        $this->except = $fieldCodes;

        return $this;
    }

    public function only(array $fieldCodes): static
    {
        $this->only = $fieldCodes;

        return $this;
    }

    /**
     * @return Collection<int, CustomFieldSection>
     */
    protected function getFilteredSections(): Collection
    {
        // Use a static cache within the request to prevent duplicate queries
        static $sectionsCache = [];
        
        $cacheKey = get_class($this) . ':' . $this->model::class . ':' . 
                   md5(serialize($this->only) . serialize($this->except));
        
        if (isset($sectionsCache[$cacheKey])) {
            return $sectionsCache[$cacheKey];
        }

        /** @var Collection<int, CustomFieldSection> $sections */
        $sections = $this->sections
            ->with(['fields' => function ($query): void {
                $query
                    ->when($this instanceof TableBuilder, fn ($q) => $q->visibleInList())
                    ->when($this instanceof InfolistBuilder, fn ($q) => $q->visibleInView())
                    ->when($this->only !== [], fn ($q) => $q->whereIn('code', $this->only))
                    ->when($this->except !== [], fn ($q) => $q->whereNotIn('code', $this->except))
                    ->orderBy('sort_order');
            }])
            ->get();

        $filteredSections = $sections->filter(fn (CustomFieldSection $section) => $section->fields->isNotEmpty());
        
        $sectionsCache[$cacheKey] = $filteredSections;
        
        return $filteredSections;
    }
}
