<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingsService;

class SyncEnvSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'settings:sync-env';

    /**
     * The console command description.
     */
    protected $description = 'Sync settings from .env file to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing settings from .env to database...');
        
        $envMappings = [
            // App settings
            'APP_NAME' => ['key' => 'site_name', 'type' => 'string', 'category' => 'general'],
            'APP_TIMEZONE' => ['key' => 'site_timezone', 'type' => 'string', 'category' => 'general'],
            'APP_LOCALE' => ['key' => 'site_language', 'type' => 'string', 'category' => 'general'],
            
            // Mail settings
            'MAIL_FROM_ADDRESS' => ['key' => 'mail_from_address', 'type' => 'email', 'category' => 'email'],
            'MAIL_FROM_NAME' => ['key' => 'mail_from_name', 'type' => 'string', 'category' => 'email'],
            'MAIL_HOST' => ['key' => 'mail_host', 'type' => 'string', 'category' => 'email'],
            'MAIL_PORT' => ['key' => 'mail_port', 'type' => 'integer', 'category' => 'email'],
            'MAIL_USERNAME' => ['key' => 'mail_username', 'type' => 'string', 'category' => 'email'],
            'MAIL_PASSWORD' => ['key' => 'mail_password', 'type' => 'string', 'category' => 'email'],
            'MAIL_ENCRYPTION' => ['key' => 'mail_encryption', 'type' => 'string', 'category' => 'email'],
            
            // Session settings
            'SESSION_LIFETIME' => ['key' => 'session_lifetime', 'type' => 'integer', 'category' => 'security'],
            
            // Cache settings
            'CACHE_STORE' => ['key' => 'cache_driver', 'type' => 'string', 'category' => 'performance'],
        ];
        
        $synced = 0;
        
        foreach ($envMappings as $envKey => $mapping) {
            $envValue = env($envKey);
            
            if ($envValue !== null) {
                SettingsService::set(
                    $mapping['key'],
                    $envValue,
                    $mapping['type'],
                    $mapping['category']
                );
                
                $this->line("Synced {$envKey} -> {$mapping['key']} = {$envValue}");
                $synced++;
            }
        }
        
        $this->info("Synced {$synced} settings from .env to database.");
        
        return 0;
    }
}
