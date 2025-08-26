<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Support;

class FieldTypeUtils
{
    public static function isDatePickerNative(): bool
    {
        return config('custom-fields.field_types.configuration.date.native', false);
    }

    public static function getDateFormat(): string
    {
        return config('custom-fields.field_types.configuration.date.format', 'Y-m-d');
    }

    public static function isDateTimePickerNative(): bool
    {
        return config('custom-fields.field_types.configuration.date_time.native', false);
    }

    public static function getDateTimeFormat(): string
    {
        return config('custom-fields.field_types.configuration.date_time.format', 'Y-m-d H:i:s');
    }
}
