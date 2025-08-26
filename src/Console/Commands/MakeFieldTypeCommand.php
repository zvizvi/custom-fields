<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeFieldTypeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'make:field-type {name : The name of the field type}
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new custom field type';

    /**
     * The type of class being generated.
     */
    protected $type = 'FieldType';

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/../../../stubs/field-type.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Filament\FieldTypes';
    }

    /**
     * Get the destination class path.
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        
        // Get the directory path and base name
        $nameWithoutRoot = ltrim($name, '\\/');
        $pathParts = explode('\\', $nameWithoutRoot);
        $baseName = array_pop($pathParts);
        
        // Ensure the filename matches the class name (with FieldType suffix)
        if (! Str::endsWith($baseName, 'FieldType')) {
            $baseName .= 'FieldType';
        }
        
        // Rebuild the path with proper filename
        $directory = implode('/', $pathParts);
        $fullPath = $this->laravel['path'];
        
        if (! empty($directory)) {
            $fullPath .= '/' . $directory;
        }
        
        return $fullPath . '/' . $baseName . '.php';
    }

    /**
     * Build the class with the given name.
     */
    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        $fieldTypeName = $this->getFieldTypeName($name);
        $fieldKey = Str::kebab($fieldTypeName);
        $fieldLabel = Str::title(Str::replace('-', ' ', $fieldKey));

        $replacements = [
            '{{ namespace }}' => $this->getNamespace($name),
            '{{ class }}' => $this->getClassName($name),
            '{{ fieldKey }}' => $fieldKey,
            '{{ fieldLabel }}' => $fieldLabel,
            '{{ fieldTypeName }}' => $fieldTypeName,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    /**
     * Get the class name from the full name.
     */
    protected function getClassName($name): string
    {
        $className = class_basename($name);
        
        // Only add FieldType suffix if it doesn't already have it
        if (! Str::endsWith($className, 'FieldType')) {
            $className .= 'FieldType';
        }
        
        return $className;
    }

    /**
     * Get the field type name (without "FieldType" suffix).
     */
    protected function getFieldTypeName($name): string
    {
        $className = class_basename($name);
        
        return Str::replaceLast('FieldType', '', $className);
    }

    /**
     * Handle the command execution.
     */
    public function handle(): bool
    {
        $name = $this->getNameInput();
        
        // Resolve the full class name with namespace
        $name = $this->qualifyClass($name);

        $path = $this->getPath($name);

        // Check if the field type already exists
        if ((! $this->hasOption('force') || ! $this->option('force')) && $this->alreadyExists($name)) {
            $this->error(sprintf('%s already exists!', $this->type));

            return false;
        }

        // Make sure the directory exists
        $this->makeDirectory($path);

        // Generate the field type class
        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info(sprintf('%s created successfully.', $this->type));
        $this->line(sprintf('<comment>%s</comment>', $path));

        // Show next steps
        $this->newLine();
        $this->line('<comment>Next steps:</comment>');
        $this->line('1. Register your field type in a service provider');
        $this->line('2. Create corresponding form component, table column, and infolist entry classes');
        $this->line('3. Update the field type class with proper component class references');
        $this->line('4. Add validation rules and configure field properties as needed');

        return true;
    }
}