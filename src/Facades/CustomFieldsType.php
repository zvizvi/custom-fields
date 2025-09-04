<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Facades;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Relaticle\CustomFields\Data\FieldTypeData;
use Relaticle\CustomFields\FieldSystem\FieldManager;

/**
 * @method static Collection<string, FieldTypeData> toCollection()
 *
 * @see FieldManager
 */
class CustomFieldsType extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FieldManager::class;
    }

    /**
     * @param  array<string, array<int | string, string | int> | string> | Closure  $fieldTypes
     */
    public static function register(array|Closure $fieldTypes): void
    {
        static::resolved(function (FieldManager $fieldTypeManager) use ($fieldTypes): void {
            $fieldTypeManager->register($fieldTypes);
        });
    }
}
