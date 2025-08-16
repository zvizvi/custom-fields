<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Filament\Integration\Builders;

use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Relaticle\CustomFields\CustomFields;
use Relaticle\CustomFields\Models\Contracts\HasCustomFields;
use Relaticle\CustomFields\Models\CustomFieldSection;

abstract class BaseBuilder
{
    protected Model & HasCustomFields $model;

    protected Builder $sections;

    protected array $except = [];

    protected array $only = [];

    public function forSchema(Schema $schema): static
    {
        /** @var Model & HasCustomFields $model */
        $model = $schema->getRecord() ?? $schema->getModel();

        return $this->forModel($model);
    }

    public function forModel(Model | string $model): static
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

        if (! $this instanceof TableBuilder) {
            $model->load('customFieldValues.customField.options');
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
                    ->with('options')
                    ->orderBy('sort_order');
            }])
            ->get();

        $filteredSections = $sections->filter(fn (CustomFieldSection $section) => $section->fields->isNotEmpty());

        $sectionsCache[$cacheKey] = $filteredSections;

        return $filteredSections;
    }
}
