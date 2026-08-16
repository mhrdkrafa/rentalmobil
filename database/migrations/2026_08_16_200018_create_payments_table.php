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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->enum('payment_type', ['dp', 'pelunasan', 'refund']);
            $table->enum('method', ['gateway', 'manual_transfer', 'cash']);
            $table->decimal('amount', 12, 2);
            $table->string('gateway_transaction_id', 100)->nullable();
            $table->string('proof_file_path', 255)->nullable();
            $table->enum('status', ['pending', 'verified', 'failed'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
