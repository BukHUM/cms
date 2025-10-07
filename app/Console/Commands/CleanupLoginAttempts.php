<?php

namespace App\Console\Commands;

use App\Services\LoginSecurityService;
use Illuminate\Console\Command;

class CleanupLoginAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:cleanup-login-attempts {--days=30 : Number of days to keep login attempts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old login attempts to maintain database performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $loginService = new LoginSecurityService();
        
        $this->info("Cleaning up login attempts older than {$days} days...");
        
        $deletedCount = $loginService->cleanupOldAttempts($days);
        
        $this->info("Successfully deleted {$deletedCount} old login attempts.");
        
        return Command::SUCCESS;
    }
}