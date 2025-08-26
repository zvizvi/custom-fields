<?php

use Relaticle\CustomFields\Entities\Configuration\EntityConfiguration;
use Relaticle\CustomFields\Entities\Configuration\EntityModel;
use Relaticle\CustomFields\Enums\EntityFeature;

return [
    /*
    |--------------------------------------------------------------------------
    | Entity Configuration
    |--------------------------------------------------------------------------
    |
    | Configure entities (models that can have custom fields) using the
    | clean, type-safe fluent builder interface.
    |
    */
    'entity_configuration' => EntityConfiguration::configure()
        ->discover(app_path('Models'))
        ->cache(true)
        ->models([
            // Example entity configurations
            // EntityModel::for(\App\Models\Post::class)
            //     ->label('Blog Post', 'Blog Posts')
            //     ->icon('heroicon-o-document-text')
            //     ->searchIn(['title', 'content'])
            //     ->features([EntityFeature::CUSTOM_FIELDS, EntityFeature::LOOKUP_SOURCE])
            //     ->priority(10),
            //
            // EntityModel::for(\App\Models\User::class)
            //     ->label('User')
            //     ->features([EntityFeature::LOOKUP_SOURCE]),
        ]),

    /*
    |--------------------------------------------------------------------------
    | Features Configuration
    |--------------------------------------------------------------------------
    |
    | Enable or disable package features. All features are enabled by default.
    |
    */
    'features' => [
        'conditional_visibility' => true,
        'encryption' => true,
        'select_option_colors' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Types Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which field types are available and their behavior.
    |
    */
    'field_types' => [
        'enabled' => [
            // Empty array = all field types enabled (default)
            // Specify field type keys to allow only those types:
            // 'text', 'textarea', 'select', 'checkbox', 'number', 'date'
        ],
        'disabled' => [
            // Specify field type keys to disable:
            // 'rich_editor', 'markdown_editor'
        ],
        'configuration' => [
            'date' => [
                'native' => false,
                'format' => 'Y-m-d',
                'display_format' => null,
            ],
            'date_time' => [
                'native' => false,
                'format' => 'Y-m-d H:i:s',
                'display_format' => null,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Configuration
    |--------------------------------------------------------------------------
    |
    | Customize the behavior of entity resources in Filament.
    |
    */
    'resource' => [
        'table' => [
            'columns' => true,
            'columns_toggleable' => true,
            'filters' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Management Interface
    |--------------------------------------------------------------------------
    |
    | Configure the Custom Fields management interface in Filament.
    |
    */
    'management' => [
        'enabled' => true,
        'slug' => 'custom-fields',
        'navigation_sort' => -1,
        'navigation_group' => true,
        'cluster' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy
    |--------------------------------------------------------------------------
    |
    | Enable multi-tenancy support with automatic tenant isolation.
    |
    */
    'tenant_aware' => false,

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configure database table names and migration paths.
    |
    */
    'database' => [
        'migrations_path' => database_path('custom-fields'),
        'table_names' => [
            'custom_field_sections' => 'custom_field_sections',
            'custom_fields' => 'custom_fields',
            'custom_field_values' => 'custom_field_values',
            'custom_field_options' => 'custom_field_options',
        ],
        'column_names' => [
            'tenant_foreign_key' => 'tenant_id',
        ],
    ],
];
