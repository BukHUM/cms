<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\SettingsHelper;

class TestSettingsCommand extends Command
{
    protected $signature = 'settings:test';
    protected $description = 'Test the Settings system';

    public function handle()
    {
        $this->info('Testing Settings System...');
        
        // Test 1: Set and get a value
        $this->info('Test 1: Setting and getting values');
        $result = SettingsHelper::set('test_key', 'test_value');
        $this->line('Set test_key: ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        $value = SettingsHelper::get('test_key');
        $this->line('Get test_key: ' . $value);
        
        // Test 2: Get non-existent key with default
        $this->info('Test 2: Getting non-existent key with default');
        $defaultValue = SettingsHelper::get('non_existent_key', 'default_value');
        $this->line('Non-existent key with default: ' . $defaultValue);
        
        // Test 3: Test env-only settings
        $this->info('Test 3: Testing env-only settings');
        $appKey = SettingsHelper::get('APP_KEY', 'not_found');
        $this->line('APP_KEY (should come from config): ' . substr($appKey, 0, 20) . '...');
        
        // Test 4: Test multiple settings
        $this->info('Test 4: Testing multiple settings');
        $settings = SettingsHelper::getMultiple(['test_key', 'site_name', 'non_existent']);
        $this->line('Multiple settings: ' . json_encode($settings));
        
        // Test 5: Test all settings
        $this->info('Test 5: Testing all settings');
        $allSettings = SettingsHelper::getAll();
        $this->line('Total settings count: ' . count($allSettings));
        
        // Test 6: Test modifiable settings
        $this->info('Test 6: Testing modifiable settings');
        $modifiableSettings = SettingsHelper::getModifiableSettings();
        $this->line('Modifiable settings count: ' . count($modifiableSettings));
        
        // Test 7: Test env-only settings
        $this->info('Test 7: Testing env-only settings');
        $envOnlySettings = SettingsHelper::getEnvOnlySettings();
        $this->line('Env-only settings count: ' . count($envOnlySettings));
        
        // Clean up
        SettingsHelper::delete('test_key');
        $this->info('Cleanup: Deleted test_key');
        
        $this->info('All tests completed!');
        
        return 0;
    }
}

