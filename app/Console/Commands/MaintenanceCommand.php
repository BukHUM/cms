<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'maintenance {action : on|off|status} {--message= : Custom maintenance message}';

    /**
     * The console command description.
     */
    protected $description = 'Manage maintenance mode';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $message = $this->option('message');

        switch ($action) {
            case 'on':
                $this->enableMaintenance($message);
                break;
            case 'off':
                $this->disableMaintenance();
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
     * Enable maintenance mode
     */
    private function enableMaintenance($message = null)
    {
        set_setting('maintenance_mode', true, 'boolean', 'general');
        
        if ($message) {
            set_setting('maintenance_message', $message, 'string', 'general');
        }
        
        clear_settings_cache();
        
        $this->info('Maintenance mode enabled');
        $this->line('Message: ' . setting('maintenance_message'));
    }

    /**
     * Disable maintenance mode
     */
    private function disableMaintenance()
    {
        set_setting('maintenance_mode', false, 'boolean', 'general');
        clear_settings_cache();
        
        $this->info('Maintenance mode disabled');
    }

    /**
     * Show maintenance status
     */
    private function showStatus()
    {
        $isEnabled = setting('maintenance_mode', false);
        $message = setting('maintenance_message', 'ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ');
        
        if ($isEnabled) {
            $this->warn('Maintenance mode is ENABLED');
            $this->line('Message: ' . $message);
        } else {
            $this->info('Maintenance mode is DISABLED');
        }
    }
}