<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('core_settings', function (Blueprint $table) {
            // Drop the existing unique constraint on 'key' column
            $table->dropUnique(['key']);
            
            // Add new composite unique constraint on 'key' and 'category'
            $table->unique(['key', 'category'], 'core_settings_key_category_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('core_settings', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('core_settings_key_category_unique');
            
            // Restore the original unique constraint on 'key' column
            $table->unique('key');
        });
    }
};