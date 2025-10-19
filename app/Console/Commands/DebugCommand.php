<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'debug {action : on|off|status} {--bar : Also toggle debug bar}';

    /**
     * The console command description.
     */
    protected $description = 'Manage debug mode and debug bar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $includeBar = $this->option('bar');

        switch ($action) {
            case 'on':
                $this->enableDebug($includeBar);
                break;
            case 'off':
                $this->disableDebug();
                break;
            case 'status':
                $this->showStatus();
                break;
            default:
                $this->error('Invalid action. Use: on, off, or status');
                return 1;
        }

        return 0;
    }

    /**
     * Enable debug mode
     */
    private function enableDebug($includeBar = false)
    {
        set_setting('debug_mode', true, 'boolean', 'general');
        
        if ($includeBar) {
            set_setting('debug_bar', true, 'boolean', 'general');
        }
        
        clear_settings_cache();
        
        $this->info('Debug mode enabled');
        if ($includeBar) {
            $this->line('Debug bar enabled');
        }
        
        $this->warn('Remember to clear cache: php artisan cache:clear');
    }

    /**
     * Disable debug mode
     */
    private function disableDebug()
    {
        set_setting('debug_mode', false, 'boolean', 'general');
        set_setting('debug_bar', false, 'boolean', 'general');
        clear_settings_cache();
        
        $this->info('Debug mode disabled');
        $this->line('Debug bar disabled');
        
        $this->warn('Remember to clear cache: php artisan cache:clear');
    }

    /**
     * Show debug status
     */
    private function showStatus()
    {
        $debugMode = setting('debug_mode', false);
        $debugBar = setting('debug_bar', false);
        
        $this->line('Debug Mode: ' . ($debugMode ? '<fg=green>ENABLED</>' : '<fg=red>DISABLED</>'));
        $this->line('Debug Bar: ' . ($debugBar ? '<fg=green>ENABLED</>' : '<fg=red>DISABLED</>'));
        
        if ($debugMode) {
            $this->warn('Debug mode is enabled. This should only be used in development!');
        }
        
        if ($debugBar && !$debugMode) {
            $this->warn('Debug bar is enabled but debug mode is disabled. Debug bar will not work.');
        }
    }
}