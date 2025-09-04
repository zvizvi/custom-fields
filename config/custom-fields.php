<?php

use Relaticle\CustomFields\Entities\Configuration\EntityConfiguration;
use Relaticle\CustomFields\Entities\Configuration\EntityModel;
use Relaticle\CustomFields\Enums\EntityFeature;
use Relaticle\CustomFields\Enums\ValidationRule;
use Relaticle\CustomFields\FieldSystem\FieldSettings;
use Relaticle\CustomFields\FieldSystem\SystemConfig;

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
        ]),

    /*
    |--------------------------------------------------------------------------
    | Advanced Field Type Configuration
    |--------------------------------------------------------------------------
    |
    | Configure field types using the powerful fluent builder API.
    | This provides advanced control over validation, security, and behavior.
    |
    */
    'field_type_configuration' => SystemConfig::configure()
        // Control which field types are available globally
        ->enabled([]) // Empty = all enabled, or specify: ['text', 'email', 'select']
        ->disabled(['rich-editor']) // Disable specific field types
        ->discover(true)
        ->cache(enabled: true, ttl: 3600)
        ->fieldTypes([
            // Example: Configure file upload field type with Filament-compatible settings
            FieldSettings::for('file_upload')
                ->label('File Upload')
                ->icon('heroicon-o-paper-clip')
                ->priority(17)
                ->defaultValidationRules([ValidationRule::FILE])
                ->availableValidationRules([
                    ValidationRule::REQUIRED,
                    ValidationRule::FILE,
                    ValidationRule::MIMES,
                    ValidationRule::MIMETYPES,
                    ValidationRule::MAX,
                ])
                ->settings([
                    // Direct Filament FileUpload method calls - any method can be used
                    'disk' => 'public',
                    'directory' => 'uploads/custom-fields',
                    'maxSize' => 10240, // 10MB
                    'acceptedFileTypes' => [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        //                        'image/jpeg',
                        //                        'image/png',
                    ],
                    'multiple' => false,
                    'maxFiles' => 1,
                    'previewable' => true,
                    'downloadable' => true,
                    'openable' => true,
                    'preserveFilenames' => false, // Security: don't preserve original names
                ]),
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
