<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Filament\Integration\Components\Forms;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Relaticle\CustomFields\Filament\Integration\Base\AbstractFormComponent;
use Relaticle\CustomFields\Models\CustomField;

final readonly class PhoneComponent extends AbstractFormComponent
{
    public function create(CustomField $customField): Field
    {
        $settings = $this->getConfigurationSettings('phone');
        $defaults = [
            'tel' => true, // Client-side hint for mobile keyboards
            'maxLength' => 20,
            'suffixIcon' => 'heroicon-m-phone',
            'autocomplete' => 'tel',
            'type' => 'tel',
        ];

        $component = TextInput::make($customField->getFieldName());

        return $this->applySettingsToComponent($component, array_merge($defaults, $settings));
    }
}
