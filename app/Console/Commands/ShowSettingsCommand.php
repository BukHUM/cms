<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class ShowSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'settings:show {category?}';

    /**
     * The console command description.
     */
    protected $description = 'Show current settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $category = $this->argument('category');
        
        $query = Setting::query();
        
        if ($category) {
            $query->where('category', $category);
            $this->info("Settings for category: {$category}");
        } else {
            $this->info("All Settings:");
        }
        
        $settings = $query->orderBy('category')->orderBy('key')->get();
        
        if ($settings->isEmpty()) {
            $this->warn('No settings found.');
            return 0;
        }
        
        $headers = ['Key', 'Value', 'Type', 'Category', 'Group', 'Active', 'Public'];
        $rows = [];
        
        foreach ($settings as $setting) {
            $rows[] = [
                $setting->key,
                $setting->value,
                $setting->type,
                $setting->category,
                $setting->group_name,
                $setting->is_active ? 'Yes' : 'No',
                $setting->is_public ? 'Yes' : 'No',
            ];
        }
        
        $this->table($headers, $rows);
        
        $this->info("Total: {$settings->count()} settings");
        
        return 0;
    }
}
