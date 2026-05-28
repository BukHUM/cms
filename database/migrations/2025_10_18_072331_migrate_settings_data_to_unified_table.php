<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data from old tables to new unified table
        $this->migrateGeneralSettings();
        $this->migratePerformanceSettings();
        $this->migrateBackupSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only migrates data, so we don't need to reverse it
        // The old tables will be dropped in separate migrations
    }

    /**
     * Migrate general settings
     */
    private function migrateGeneralSettings()
    {
        if (Schema::hasTable('core_settings_general')) {
            $generalSettings = DB::table('core_settings_general')->get();
            
            foreach ($generalSettings as $setting) {
                DB::table('core_settings')->insert([
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'category' => 'general',
                    'group_name' => $setting->group,
                    'description' => $setting->description,
                    'is_active' => $setting->is_public,
                    'is_public' => $setting->is_public,
                    'sort_order' => 0,
                    'created_at' => $setting->created_at,
                    'updated_at' => $setting->updated_at,
                ]);
            }
        }
    }

    /**
     * Migrate performance settings
     */
    private function migratePerformanceSettings()
    {
        if (Schema::hasTable('core_settings_performance')) {
            $performanceSettings = DB::table('core_settings_performance')->get();
            
            foreach ($performanceSettings as $setting) {
                DB::table('core_settings')->insert([
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'category' => 'performance',
                    'group_name' => $setting->category,
                    'description' => $setting->description,
                    'is_active' => $setting->is_active,
                    'is_public' => false,
                    'sort_order' => $setting->sort_order ?? 0,
                    'validation_rules' => $setting->validation_rules ? json_encode($setting->validation_rules) : null,
                    'default_value' => $setting->default_value,
                    'options' => $setting->options ? json_encode($setting->options) : null,
                    'created_by' => $setting->created_by,
                    'updated_by' => $setting->updated_by,
                    'created_at' => $setting->created_at,
                    'updated_at' => $setting->updated_at,
                ]);
            }
        }
    }

    /**
     * Migrate backup settings
     */
    private function migrateBackupSettings()
    {
        if (Schema::hasTable('core_settings_backup')) {
            $backupSettings = DB::table('core_settings_backup')->get();
            
            foreach ($backupSettings as $setting) {
                DB::table('core_settings')->insert([
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'category' => 'backup',
                    'group_name' => $setting->group,
                    'description' => $setting->description,
                    'is_active' => $setting->is_active,
                    'is_public' => false,
                    'sort_order' => $setting->sort_order ?? 0,
                    'created_at' => $setting->created_at,
                    'updated_at' => $setting->updated_at,
                ]);
            }
        }
    }
};