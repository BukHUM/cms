<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingsService;

class TestSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'settings:test';

    /**
     * The console command description.
     */
    protected $description = 'Test settings functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Settings System...');
        
        // Test getting a setting
        $siteName = setting('site_name', 'Default Site');
        $this->line("Site Name: {$siteName}");
        
        // Test getting settings by category
        $generalSettings = settings('general');
        $this->line("General Settings Count: " . $generalSettings->count());
        
        // Test setting a value
        set_setting('test_setting', 'test_value', 'string', 'general');
        $this->line("Set test_setting to: test_value");
        
        // Test getting the value back
        $testValue = setting('test_setting');
        $this->line("Retrieved test_setting: {$testValue}");
        
        // Test toggling
        $this->line("Toggling test_setting status...");
        toggle_setting('test_setting');
        
        // Clear cache
        clear_settings_cache();
        $this->line("Cleared settings cache");
        
        $this->info('Settings test completed successfully!');
        
        return 0;
    }
}
