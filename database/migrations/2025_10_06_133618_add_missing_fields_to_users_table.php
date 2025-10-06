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
        Schema::table('laravel_users', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('laravel_users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }
            if (!Schema::hasColumn('laravel_users', 'avatar')) {
                $table->string('avatar')->nullable()->after('role');
            }
            if (!Schema::hasColumn('laravel_users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('avatar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laravel_users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar', 'last_login_at']);
        });
    }
};
