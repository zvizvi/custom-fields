<?php

declare(strict_types=1);

use Relaticle\CustomFields\Models\CustomField;
use Relaticle\CustomFields\Models\CustomFieldSection;
use Relaticle\CustomFields\Tests\Fixtures\Models\Post;
use Relaticle\CustomFields\Tests\Fixtures\Models\User;
use Relaticle\CustomFields\Tests\Fixtures\Resources\Posts\Pages\EditPost;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->post = Post::factory()->create();

    $this->section = CustomFieldSection::factory()->create([
        'entity_type' => Post::class,
        'name' => 'Test Section',
        'active' => true,
    ]);
});

it('debug: can see custom field components in form schema', function (): void {
    // Arrange: Create a custom field
    $customField = CustomField::factory()->create([
        'custom_field_section_id' => $this->section->id,
        'code' => 'test_field',
        'type' => 'text',
        'entity_type' => Post::class,
        'active' => true,
    ]);

    // Act: Mount the form and try to interact with it
    $component = livewire(EditPost::class, ['record' => $this->post->getKey()])
        ->assertSchemaExists('form');

    // Get the component instance
    $livewireInstance = $component->instance();

    // Access the form directly as a property (as Filament does)
    $form = $livewireInstance->form ?? null;

    if ($form) {
        dump('Form found:', get_class($form));

        // Get all components
        $allComponents = $form->getFlatComponents(withHidden: true, withAbsoluteKeys: true);

        dump('All component keys:', array_keys($allComponents));

        // Look for the custom field directly (fields are now added directly to the form, not wrapped in a container)
        $testFieldComponent = $allComponents['form.custom_fields.test_field'] ?? null;

        if ($testFieldComponent) {
            dump('Found test_field:', get_class($testFieldComponent));
            expect($testFieldComponent)->not->toBeNull('Custom field component should exist in the form schema');
        } else {
            dump('test_field NOT FOUND - expected key: form.custom_fields.test_field');
            dump('Available keys:', array_keys($allComponents));
            expect($testFieldComponent)->not->toBeNull('Custom field component should exist in the form schema');
        }
    } else {
        dump('Form schema not found in mountedSchemas');
        expect($form)->not->toBeNull();
    }
});
