<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Models\Concerns;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Relaticle\CustomFields\CustomFields;
use Relaticle\CustomFields\Enums\CustomFieldsFeature;
use Relaticle\CustomFields\FeatureSystem\FeatureManager;
use Relaticle\CustomFields\Models\Contracts\HasCustomFields;
use Relaticle\CustomFields\Models\CustomField;
use Relaticle\CustomFields\Models\CustomFieldValue;
use Relaticle\CustomFields\QueryBuilders\CustomFieldQueryBuilder;

/**
 * @see HasCustomFields
 */
trait UsesCustomFields
{
    public function __construct($attributes = [])
    {
        if (count($this->getFillable()) !== 0) {
            $this->mergeFillable(['custom_fields']);
        }

        parent::__construct($attributes);

        // Handle custom_fields immediately if present in attributes
        $this->handleCustomFields();
    }

    /**
     * @var array<string, array<string, mixed>>
     */
    protected static array $tempCustomFields = [];

    /**
     * Generate a unique key for storing temporary custom fields data.
     */
    protected function getTempCustomFieldsKey(): string
    {
        // Use class name + key for existing models, or object ID for new models
        if ($this->exists) {
            return static::class.':'.$this->getKey();
        }

        return static::class.':new:'.spl_object_id($this);
    }

    protected static function bootUsesCustomFields(): void
    {
        static::saving(function (Model $model): void {
            $model->handleCustomFields();
        });
    }

    /**
     * Override save to handle custom fields after saving.
     */
    public function save(array $options = []): bool
    {
        $result = parent::save($options);

        if ($result) {
            $this->saveCustomFieldsFromTemp();
        }

        return $result;
    }

    /**
     * Mutator to intercept custom_fields attribute and store it temporarily.
     *
     * @param  array<string, mixed>|null  $value
     */
    public function setCustomFieldsAttribute(?array $value): void
    {
        // Handle null value (when custom_fields is not provided)
        if ($value === null) {
            return;
        }

        // Store in temporary storage instead of attributes
        $key = $this->getTempCustomFieldsKey();
        self::$tempCustomFields[$key] = $value;

        // Mark the model as dirty by updating the updated_at timestamp
        // This ensures the model will be saved even if no other attributes changed
        if ($this->usesTimestamps() && ! $this->isDirty('updated_at')) {
            $this->updated_at = $this->freshTimestamp();
        }
    }

    /**
     * Handle the custom fields before saving the model.
     */
    protected function handleCustomFields(): void
    {
        if (isset($this->attributes['custom_fields']) && is_array($this->attributes['custom_fields'])) {
            $key = $this->getTempCustomFieldsKey();
            self::$tempCustomFields[$key] = $this->attributes['custom_fields'];
            unset($this->attributes['custom_fields']);
        }
    }

    /**
     * Save custom fields from temporary storage after the model is created/updated.
     */
    protected function saveCustomFieldsFromTemp(): void
    {
        $key = $this->getTempCustomFieldsKey();

        if (isset(self::$tempCustomFields[$key]) && method_exists($this, 'saveCustomFields')) {
            $this->saveCustomFields(self::$tempCustomFields[$key]);
            unset(self::$tempCustomFields[$key]);
        }
    }

    /**
     * @return CustomFieldQueryBuilder<CustomField>
     */
    public function customFields(): CustomFieldQueryBuilder
    {
        return CustomFields::newCustomFieldModel()->query()->forEntity($this::class);
    }

    /**
     * @return MorphMany<CustomFieldValue>
     */
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFields::valueModel(), 'entity');
    }

    public function scopeWithCustomFieldValues(Builder $query): Builder
    {
        return $query->with('customFieldValues.customField.options');
    }

    public function getCustomFieldValue(CustomField $customField): mixed
    {
        $fieldValue = $this->customFieldValues
            ->firstWhere('custom_field_id', $customField->getKey())
            ?->getValue();

        if (empty($fieldValue)) {
            return $fieldValue;
        }

        if ($customField->settings?->encrypted) {
            $fieldValue = Crypt::decryptString($fieldValue);
        }

        return $fieldValue instanceof Collection
            ? $fieldValue->toArray()
            : $fieldValue;
    }

    public function saveCustomFieldValue(CustomField $customField, mixed $value, ?Model $tenant = null): void
    {
        $data = ['custom_field_id' => $customField->getKey()];

        if (FeatureManager::isEnabled(CustomFieldsFeature::SYSTEM_MULTI_TENANCY)) {
            $data[config('custom-fields.database.column_names.tenant_foreign_key')] = $this->resolveTenantId($tenant, $customField);
        }

        $customFieldValue = $this->customFieldValues();

        if ($customField->settings?->encrypted) {
            $customFieldValue->withCasts([$customField->getValueColumn() => 'encrypted']);
        }

        $customFieldValue = $customFieldValue->firstOrNew($data);
        $customFieldValue->setValue($value);
        $customFieldValue->save();
    }

    /**
     * Resolve the tenant ID from available sources
     */
    protected function resolveTenantId(?Model $tenant, CustomField $customField): mixed
    {
        // First priority: Explicitly provided tenant
        if ($tenant instanceof Model) {
            return $tenant->getKey();
        }

        // Second priority: Current Filament tenant
        $filamentTenant = Filament::getTenant();
        if ($filamentTenant !== null) {
            return $filamentTenant->getKey();
        }

        // Fallback: Use the tenant from the custom field
        $tenantColumn = config('custom-fields.database.column_names.tenant_foreign_key');

        return $customField->{$tenantColumn};
    }

    /**
     * @param  array<string, mixed>  $customFields
     */
    public function saveCustomFields(array $customFields, ?Model $tenant = null): void
    {
        $this->customFields()->each(function (CustomField $customField) use ($customFields, $tenant): void {
            $value = $customFields[$customField->code] ?? null;
            $this->saveCustomFieldValue($customField, $value, $tenant);
        });
    }
}
