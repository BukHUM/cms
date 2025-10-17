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
        // เปลี่ยนชื่อตาราง migrations เป็น core_migrations เพื่อให้สอดคล้องกับ concept
        if (Schema::hasTable('migrations') && !Schema::hasTable('core_migrations')) {
            Schema::rename('migrations', 'core_migrations');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ย้อนกลับการเปลี่ยนชื่อตาราง
        if (Schema::hasTable('core_migrations') && !Schema::hasTable('migrations')) {
            Schema::rename('core_migrations', 'migrations');
        }
    }
};