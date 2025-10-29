<?php

namespace Relaticle\CustomFields\Tests\Fixtures\Resources\Posts\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Relaticle\CustomFields\Tests\Fixtures\Resources\Posts\PostResource;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Extract custom fields before creating the record
        $customFields = Arr::pull($data, 'custom_fields');

        // Create the record with remaining data
        $record = static::getModel()::create($data);

        // Save custom fields if they exist
        if ($customFields && method_exists($record, 'saveCustomFields')) {
            $record->saveCustomFields($customFields);
        }

        return $record;
    }
}
