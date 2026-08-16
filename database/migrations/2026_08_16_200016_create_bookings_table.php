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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20)->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
            $table->boolean('with_driver')->default(false);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('pickup_location', 255)->nullable();
            $table->string('dropoff_location', 255)->nullable();
            $table->smallInteger('total_days');
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->decimal('dp_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'ongoing', 'completed', 'cancelled', 'rejected'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'dp_paid', 'paid_full', 'refunded'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
