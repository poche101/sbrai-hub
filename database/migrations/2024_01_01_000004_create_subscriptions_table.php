<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuid('vendor_id');
            $table->enum('status', ['active', 'inactive', 'expired', 'cancelled'])->default('inactive');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_method', ['paystack', 'espees'])->default('paystack');
            $table->string('transaction_id')->nullable();
            $table->string('payment_gateway', 50)->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['vendor_id', 'status']);
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
