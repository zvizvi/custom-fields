<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Filament\Integration\Components\Forms;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Relaticle\CustomFields\Filament\Integration\Base\AbstractFormComponent;
use Relaticle\CustomFields\Models\CustomField;

final readonly class DateTimeComponent extends AbstractFormComponent
{
    public function create(CustomField $customField): Field
    {
        return DateTimePicker::make($customField->getFieldName())
            ->native(false)
            ->format('Y-m-d H:i:s')
            ->displayFormat('Y-m-d H:i:s')
            ->placeholder('YYYY-MM-DD HH:MM:SS');
    }
}
