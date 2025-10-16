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
        Schema::create('laravel_backup_history', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom backup ID
            $table->string('type')->default('database'); // database, files, both
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->bigInteger('file_size')->default(0); // File size in bytes
            $table->string('file_path')->nullable(); // Path to backup file
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['status', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_backup_history');
    }
};
