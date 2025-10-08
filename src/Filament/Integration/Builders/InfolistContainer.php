<?php

namespace Relaticle\CustomFields\Filament\Integration\Builders;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;

final class InfolistContainer extends Component
{
    protected string $view = 'filament-schemas::components.grid';

    protected Model|string|null $recordModel;

    protected array $except = [];

    protected array $only = [];

    private bool $hiddenLabels = false;

    private bool $visibleWhenFilled = false;

    private bool $withoutSections = false;

    public function __construct()
    {
        // Defer schema generation until we can safely access the record
        $this->schema(fn() => $this->generateSchema());
    }

    public static function make(): static
    {
        return app(self::class);
    }

    public function forModel(Model|string|null $model): static
    {
        $this->recordModel = $model;

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

    /**
     * @return array<int, Field>
     */
    protected function generateSchema(): array
    {
        $model = $this->recordModel ?? $this->getRecord() ?? $this->getModel();

        $builder = app(InfolistBuilder::class);

        return $builder
            ->only($this->only)
            ->except($this->except)
            ->hiddenLabels($this->hiddenLabels)
            ->visibleWhenFilled($this->visibleWhenFilled)
            ->withoutSections($this->withoutSections)
            ->values($model)
            ->toArray();
    }
}
