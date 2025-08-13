<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Commands;

use Illuminate\Console\Command;

class UpgradeCommand extends Command
{
    protected $signature = 'custom-fields:upgrade';

    protected $description = 'Upgrade Custom Fields from V1 to V2';

    public function handle(): int
    {
        $this->info('🚀 Custom Fields Upgrade Tool (v1 → v2)');
        $this->info('=====================================');
        $this->newLine();


        return Command::SUCCESS;
    }
}