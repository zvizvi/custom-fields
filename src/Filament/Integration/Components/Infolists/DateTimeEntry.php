<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Filament\Integration\Components\Infolists;

use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry;
use Relaticle\CustomFields\Filament\Integration\Base\AbstractInfolistEntry;
use Relaticle\CustomFields\Models\CustomField;
use Relaticle\CustomFields\Support\FieldTypeUtils;

final class DateTimeEntry extends AbstractInfolistEntry
{
    public function make(CustomField $customField): Entry
    {
        return TextEntry::make($customField->getFieldName())
            ->dateTime(FieldTypeUtils::getDateTimeFormat())
            ->placeholder(FieldTypeUtils::getDateTimeFormat())
            ->label($customField->name)
            ->state(fn ($record) => $record->getCustomFieldValue($customField));
    }
}
