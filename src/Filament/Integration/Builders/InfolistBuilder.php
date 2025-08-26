<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Filament\Integration\Builders;

use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Collection;
use Relaticle\CustomFields\Filament\Integration\Factories\FieldInfolistsFactory;
use Relaticle\CustomFields\Filament\Integration\Factories\SectionInfolistsFactory;
use Relaticle\CustomFields\Models\CustomField;
use Relaticle\CustomFields\Models\CustomFieldSection;
use Relaticle\CustomFields\Services\Visibility\BackendVisibilityService;

final class InfolistBuilder extends BaseBuilder
{
    private bool $hiddenLabels = false;

    private bool $visibleWhenFilled = false;

    private bool $withoutSections = false;

    public function build(): Component
    {
        return Grid::make(1)->schema($this->values()->toArray());
    }

    /**
     * @return Collection<int, mixed>
     */
    public function values(): Collection
    {
        $fieldInfolistsFactory = app(FieldInfolistsFactory::class);
        $sectionInfolistsFactory = app(SectionInfolistsFactory::class);
        $backendVisibilityService = app(BackendVisibilityService::class);

        $createField = fn (CustomField $customField) => $fieldInfolistsFactory->create($customField)
            ->hiddenLabel($this->hiddenLabels)
            ->when($this->visibleWhenFilled, fn ($field): Entry => $field->visible(fn ($state) => filled($state)));

        $getVisibleFields = fn (CustomFieldSection $section) => $backendVisibilityService
            ->getVisibleFields($this->model, $section->fields)
            ->map($createField);

        if ($this->withoutSections) {
            return $this->getFilteredSections()
                ->flatMap($getVisibleFields)
                ->filter();
        }

        return $this->getFilteredSections()
            ->map(function (CustomFieldSection $section) use ($sectionInfolistsFactory, $getVisibleFields) {
                $fields = $getVisibleFields($section);

                return $fields->isEmpty()
                    ? null
                    : $sectionInfolistsFactory->create($section)->schema($fields->toArray());
            })
            ->filter();
    }

    public function hiddenLabels(bool $hiddenLabels = true): static
    {
        $this->hiddenLabels = $hiddenLabels;

        return $this;
    }

    public function visibleWhenFilled(bool $visibleWhenFilled = true): static
    {
        $this->visibleWhenFilled = $visibleWhenFilled;

        return $this;
    }

    public function withoutSections(bool $withoutSections = true): static
    {
        $this->withoutSections = $withoutSections;

        return $this;
    }
}
