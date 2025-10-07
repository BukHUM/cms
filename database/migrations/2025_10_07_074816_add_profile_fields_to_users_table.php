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
            if (!Schema::hasColumn('laravel_users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('laravel_users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('laravel_users', 'bio')) {
                $table->text('bio')->nullable()->after('address');
            }
            if (!Schema::hasColumn('laravel_users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('laravel_users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('avatar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laravel_users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'bio', 'avatar', 'email_verified_at']);
        });
    }
};