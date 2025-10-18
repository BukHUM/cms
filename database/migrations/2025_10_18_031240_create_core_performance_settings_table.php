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
        Schema::create('core_performance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->text('value');
            $table->enum('type', ['string', 'integer', 'float', 'boolean', 'array', 'json'])->default('string');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('category');
            $table->integer('sort_order')->default(0);
            $table->json('validation_rules')->nullable();
            $table->text('default_value')->nullable();
            $table->json('options')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['category', 'is_active']);
            $table->index(['type']);
            $table->index(['sort_order']);
            $table->index(['created_by']);
            $table->index(['updated_by']);

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('core_users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('core_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_performance_settings');
    }
};