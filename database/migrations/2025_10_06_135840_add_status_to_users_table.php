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
            // เพิ่มคอลัมน์ที่จำเป็นสำหรับระบบสิทธิ์
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('active')->after('avatar');
            
            // เพิ่มดัชนี
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laravel_users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status']);
        });
    }
};
