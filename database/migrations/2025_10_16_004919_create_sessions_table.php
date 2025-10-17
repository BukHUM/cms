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
        if (!Schema::hasTable('laravel_sessions')) {
            Schema::create('laravel_sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
                
                // Add security indexes
                $table->index(['user_id', 'last_activity']);
                $table->index(['ip_address', 'last_activity']);
            });
        } else {
            // Table exists, just add missing indexes if needed
            Schema::table('laravel_sessions', function (Blueprint $table) {
                if (!Schema::hasColumn('laravel_sessions', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->index();
                }
                if (!Schema::hasColumn('laravel_sessions', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable();
                }
                if (!Schema::hasColumn('laravel_sessions', 'user_agent')) {
                    $table->text('user_agent')->nullable();
                }
                if (!Schema::hasColumn('laravel_sessions', 'payload')) {
                    $table->longText('payload');
                }
                if (!Schema::hasColumn('laravel_sessions', 'last_activity')) {
                    $table->integer('last_activity')->index();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_sessions');
    }
};
