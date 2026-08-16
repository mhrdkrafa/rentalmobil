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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('vehicle_categories');
            $table->string('name', 100);
            $table->string('plate_number', 20)->unique();
            $table->enum('transmission', ['manual', 'automatic']);
            $table->enum('fuel_type', ['bensin', 'diesel', 'listrik', 'hybrid']);
            $table->tinyInteger('capacity');
            $table->year('year');
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('price_per_day_with_driver', 12, 2)->nullable();
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->tinyInteger('min_dp_percentage')->default(30);
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance', 'inactive'])->default('available');
            $table->string('location', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
