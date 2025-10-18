<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingsService;

class ClearSettingsCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'settings:clear-cache';

    /**
     * The console command description.
     */
    protected $description = 'Clear all settings cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing settings cache...');
        
        SettingsService::clearCache();
        
        $this->info('Settings cache cleared successfully!');
        
        return 0;
    }
}
