<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class UpgradeCommand extends Command
{
    protected $signature = 'custom-fields:upgrade
                            {--path= : Specific path to upgrade (defaults to app directory)}';

    protected $description = 'Upgrade Custom Fields from V1 to V2';

    /** @var array<string> */
    protected array $filesUpdated = [];

    /** @var array<array{file: string, error: string}> */
    protected array $errors = [];

    public function handle(): int
    {
        $this->info('🚀 Custom Fields Upgrade Tool (V1 → V2)');
        $this->info('=====================================');
        $this->newLine();

        // Step 1: Check database migrations
        $this->info('📊 Checking database migrations...');
        if (! $this->checkAndUpdateDatabase()) {
            return Command::FAILURE;
        }

        // Step 2: Analyze codebase for v1 usage
        $this->info('🔍 Analyzing codebase for v1 components...');
        $filesToUpdate = $this->scanForV1Usage();

        if (empty($filesToUpdate)) {
            $this->info('✅ No v1 components found. Your codebase appears to be already using v2!');
            $this->clearCaches();

            return Command::SUCCESS;
        }

        // Step 3: Show summary and confirm
        $this->newLine();
        $this->info('Found '.count($filesToUpdate).' file(s) that need updating:');
        foreach ($filesToUpdate as $file => $issues) {
            $this->line('  • '.Str::after($file, base_path().'/'));
            foreach ($issues as $issue) {
                $this->line('    - '.$issue['description']);
            }
        }

        $this->newLine();
        if (! $this->confirm('Do you want to proceed with the upgrade?', true)) {
            $this->warn('Upgrade cancelled.');

            return Command::FAILURE;
        }

        // Step 4: Perform upgrades
        $this->newLine();
        $this->info('🔧 Upgrading files...');
        $this->performUpgrades($filesToUpdate);

        // Step 5: Clear caches
        $this->clearCaches();

        // Step 6: Show results
        $this->showResults();

        return empty($this->errors) ? Command::SUCCESS : Command::FAILURE;
    }

    protected function checkAndUpdateDatabase(): bool
    {
        try {
            $optionsTable = config('custom-fields.table_names.custom_field_options', 'custom_field_options');

            if (! Schema::hasTable($optionsTable)) {
                $this->error('Custom field options table not found. Please run migrations first.');

                return false;
            }

            // Check if settings column exists (v2 requirement)
            if (! Schema::hasColumn($optionsTable, 'settings')) {
                $this->warn('Missing "settings" column in '.$optionsTable.' table.');
                $this->info('Creating migration for settings column...');
                $this->call('make:migration', [
                    'name' => 'add_settings_to_custom_field_options_table',
                    '--create' => false,
                ]);
                $this->warn('Migration created. Please add this to the migration:');
                $this->line('$table->json(\'settings\')->nullable();');
                $this->warn('Then run: php artisan migrate');

                return false;
            }

            $this->info('✅ Database structure is compatible with v2');

            return true;
        } catch (Exception $e) {
            $this->error('Database check failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * @return array<string, array<array{type: string, description: string, old?: string, new?: string, pattern?: string}>>
     */
    protected function scanForV1Usage(): array
    {
        $path = $this->option('path') ?: app_path();
        $filesToUpdate = [];

        $progressBar = new ProgressBar(new ConsoleOutput, 100);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $progressBar->setMessage('Scanning files...');
        $progressBar->start();

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $phpFiles = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $phpFiles[] = $file->getPathname();
            }
        }

        $progressBar->setMaxSteps(count($phpFiles));

        foreach ($phpFiles as $filePath) {
            $progressBar->advance();
            $progressBar->setMessage('Scanning: '.basename($filePath));

            $content = File::get($filePath);
            $issues = [];

            // Check for v1 CustomFieldsComponent
            if (preg_match('/use\s+Relaticle\\\\CustomFields\\\\Filament\\\\Forms\\\\Components\\\\CustomFieldsComponent;/', $content)) {
                $issues[] = [
                    'type' => 'import',
                    'description' => 'Uses v1 CustomFieldsComponent import',
                    'old' => 'Relaticle\\CustomFields\\Filament\\Forms\\Components\\CustomFieldsComponent',
                    'new' => 'Relaticle\\CustomFields\\Facades\\CustomFields',
                ];
            }

            if (preg_match('/CustomFieldsComponent::make\(\)/', $content)) {
                $issues[] = [
                    'type' => 'component',
                    'description' => 'Uses v1 CustomFieldsComponent::make()',
                    'pattern' => '/CustomFieldsComponent::make\(\)/',
                ];
            }

            // Check for v1 CustomFieldsInfolists
            if (preg_match('/use\s+Relaticle\\\\CustomFields\\\\Filament\\\\Infolists\\\\CustomFieldsInfolists;/', $content)) {
                $issues[] = [
                    'type' => 'import',
                    'description' => 'Uses v1 CustomFieldsInfolists import',
                    'old' => 'Relaticle\\CustomFields\\Filament\\Infolists\\CustomFieldsInfolists',
                    'new' => 'Relaticle\\CustomFields\\Facades\\CustomFields',
                ];
            }

            if (preg_match('/CustomFieldsInfolists::make\(\)/', $content)) {
                $issues[] = [
                    'type' => 'infolist',
                    'description' => 'Uses v1 CustomFieldsInfolists::make()',
                    'pattern' => '/CustomFieldsInfolists::make\(\)(\s*->columnSpanFull\(\))?/',
                ];
            }

            // Check for v1 trait namespace
            if (preg_match('/use\s+Relaticle\\\\CustomFields\\\\Filament\\\\Tables\\\\Concerns\\\\InteractsWithCustomFields;/', $content)) {
                $issues[] = [
                    'type' => 'trait_import',
                    'description' => 'Uses v1 InteractsWithCustomFields namespace',
                    'old' => 'Relaticle\\CustomFields\\Filament\\Tables\\Concerns\\InteractsWithCustomFields',
                    'new' => 'Relaticle\\CustomFields\\Concerns\\InteractsWithCustomFields',
                ];
            }

            if (! empty($issues)) {
                $filesToUpdate[$filePath] = $issues;
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        return $filesToUpdate;
    }

    /**
     * @param  array<string, array<array{type: string, description: string, old?: string, new?: string, pattern?: string}>>  $filesToUpdate
     */
    protected function performUpgrades(array $filesToUpdate): void
    {
        $progressBar = new ProgressBar($this->output, count($filesToUpdate));
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $progressBar->setMessage('Upgrading files...');
        $progressBar->start();

        foreach ($filesToUpdate as $filePath => $issues) {
            $progressBar->setMessage('Upgrading: '.basename($filePath));
            $progressBar->advance();
            $this->upgradeFile($filePath, $issues);
        }

        $progressBar->finish();
        $this->newLine(2);
    }

    /**
     * @param  array<array{type: string, description: string, old?: string, new?: string, pattern?: string}>  $issues
     */
    protected function upgradeFile(string $filePath, array $issues): void
    {
        try {
            $content = File::get($filePath);
            $originalContent = $content;

            $hasCustomFieldsFacadeImport = false;

            // Check if file already has CustomFields facade import
            if (preg_match('/use\s+Relaticle\\\\CustomFields\\\\Facades\\\\CustomFields;/', $content)) {
                $hasCustomFieldsFacadeImport = true;
            }

            // Sort issues to process imports first
            usort($issues, function ($a, $b) {
                $order = ['import' => 0, 'trait_import' => 1, 'component' => 2, 'infolist' => 3, 'table_upgrade' => 4];

                return ($order[$a['type']] ?? 99) <=> ($order[$b['type']] ?? 99);
            });

            foreach ($issues as $issue) {
                switch ($issue['type']) {
                    case 'import':
                        // Replace v1 imports with v2
                        $content = str_replace(
                            'use '.$issue['old'].';',
                            'use '.$issue['new'].';',
                            $content
                        );
                        if ($issue['new'] === 'Relaticle\\CustomFields\\Facades\\CustomFields') {
                            $hasCustomFieldsFacadeImport = true;
                        }

                        break;

                    case 'component':
                        // Update CustomFieldsComponent::make() to new builder API
                        // First ensure we have the import
                        if (! $hasCustomFieldsFacadeImport && ! preg_match('/use\s+Relaticle\\\\CustomFields\\\\Facades\\\\CustomFields;/', $content)) {
                            // Add import after namespace
                            $content = $this->addImportAfterNamespace($content, 'Relaticle\\CustomFields\\Facades\\CustomFields');
                            $hasCustomFieldsFacadeImport = true;
                        }

                        // Replace component usage - need to handle context
                        $content = $this->replaceFormComponent($content);

                        break;

                    case 'infolist':
                        // Update CustomFieldsInfolists to new builder API
                        if (! $hasCustomFieldsFacadeImport && ! preg_match('/use\s+Relaticle\\\\CustomFields\\\\Facades\\\\CustomFields;/', $content)) {
                            $content = $this->addImportAfterNamespace($content, 'Relaticle\\CustomFields\\Facades\\CustomFields');
                            $hasCustomFieldsFacadeImport = true;
                        }

                        $content = $this->replaceInfolistComponent($content);

                        break;

                    case 'trait_import':
                        // Update trait namespace
                        $content = str_replace(
                            'use '.$issue['old'].';',
                            'use '.$issue['new'].';',
                            $content
                        );

                        break;
                }
            }

            if ($content !== $originalContent) {
                // Format the file if needed
                $content = $this->formatPhpCode($content);
                File::put($filePath, $content);
                $this->filesUpdated[] = $filePath;
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'file' => $filePath,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function addImportAfterNamespace(string $content, string $import): string
    {
        // Check if import already exists
        if (str_contains($content, 'use '.$import.';')) {
            return $content;
        }

        // Find the best place to add the import
        if (preg_match('/(namespace\s+[^;]+;)\s*\n(\s*\n)?(\s*use\s+[^;]+;)?/', $content, $matches)) {
            $namespaceDeclaration = $matches[1];
            $existingUses = $matches[3] ?? '';

            if (! empty($existingUses)) {
                // Add after existing use statements
                $replacement = $matches[0]."\nuse ".$import.';';
            } else {
                // Add after namespace with proper spacing
                $replacement = $namespaceDeclaration."\n\nuse ".$import.';';
                if (! empty($matches[3])) {
                    $replacement .= "\n".$matches[3];
                }
            }

            $content = preg_replace('/(namespace\s+[^;]+;)\s*\n(\s*\n)?(\s*use\s+[^;]+;)?/', $replacement, $content, 1) ?? $content;
        }

        return $content;
    }

    protected function formatPhpCode(string $content): string
    {
        // Remove multiple blank lines
        $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content) ?? $content;

        // Ensure proper spacing after namespace
        $content = preg_replace('/(namespace\s+[^;]+;)\n([^\n])/', "$1\n\n$2", $content) ?? $content;

        // Ensure proper spacing between use statements and class declaration
        $content = preg_replace('/(use\s+[^;]+;)\n+(class|trait|interface|abstract\s+class|final\s+class)/', "$1\n\n$2", $content) ?? $content;

        return $content;
    }

    protected function replaceFormComponent(string $content): string
    {
        // Pattern to match CustomFieldsComponent::make() with possible chaining
        $pattern = '/CustomFieldsComponent::make\(\)([^,\]]*)?/';

        // We need to determine the context - is this in a form method?
        if (preg_match('/public\s+static\s+function\s+form\s*\(\s*Form\s+\$form\s*\)/', $content)) {
            // In a form context, replace with builder API
            $replacement = 'CustomFields::form()
                ->forModel($form->getRecord())
                ->build()$1';
        } else {
            // Fallback replacement
            $replacement = 'CustomFields::form()
                ->forModel($this->record ?? $this->getRecord())
                ->build()$1';
        }

        return preg_replace($pattern, $replacement, $content) ?? $content;
    }

    protected function replaceInfolistComponent(string $content): string
    {
        // Pattern to match CustomFieldsInfolists::make() with possible ->columnSpanFull()
        $pattern = '/CustomFieldsInfolists::make\(\)(\s*->columnSpanFull\(\))?/';

        // Check if we're in an infolist context
        if (preg_match('/public\s+static\s+function\s+infolist\s*\(\s*Infolist\s+\$infolist\s*\)/', $content)) {
            $replacement = 'CustomFields::infolist()
                ->forModel($infolist->getRecord())
                ->build()$1';
        } else {
            $replacement = 'CustomFields::infolist()
                ->forModel($this->record ?? $this->getRecord())
                ->build()$1';
        }

        return preg_replace($pattern, $replacement, $content) ?? $content;
    }

    protected function clearCaches(): void
    {
        $this->newLine();
        $this->info('🧹 Clearing caches...');

        $commands = [
            'cache:clear' => 'Application cache',
            'config:clear' => 'Configuration cache',
            'view:clear' => 'View cache',
        ];

        // Check if Filament is installed
        if (class_exists('Filament\\FilamentServiceProvider')) {
            $commands['filament:cache-components'] = 'Filament components';
        }

        foreach ($commands as $command => $description) {
            $this->call($command);
            $this->info('✅ Cleared '.$description);
        }
    }

    protected function showResults(): void
    {
        $this->newLine();
        $this->info('========================================');
        $this->info('🎉 Upgrade Process Complete!');
        $this->info('========================================');
        $this->newLine();

        $this->info('UPGRADE SUMMARY:');
        $this->info('✅ Files updated: '.count($this->filesUpdated));

        if (! empty($this->filesUpdated)) {
            $this->newLine();
            $this->info('Updated files:');
            foreach ($this->filesUpdated as $file) {
                $this->line('  • '.Str::after($file, base_path().'/'));
            }
        }

        if (! empty($this->errors)) {
            $this->newLine();
            $this->error('⚠️  Errors encountered:');
            foreach ($this->errors as $error) {
                $this->error('  • '.Str::after($error['file'], base_path().'/').': '.$error['error']);
            }
            $this->newLine();
            $this->warn('Please review and fix these errors manually.');
        }

        if (empty($this->errors)) {
            $this->newLine();
            $this->info('✨ Next Steps:');
            $this->line('1. Review the updated files to ensure everything looks correct');
            $this->line('2. Run your test suite to verify functionality');
            $this->line('3. Check your Filament resources in the browser');
        }

        $this->newLine();
        $this->info('For more information, visit the upgrade guide:');
        $this->line('https://custom-fields.dev/docs/v2/upgrade');
    }
}
