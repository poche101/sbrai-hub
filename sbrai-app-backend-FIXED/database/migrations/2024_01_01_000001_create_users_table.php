<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
           $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('password');
            $table->enum('role', ['buyer', 'vendor', 'admin'])->default('buyer');
            $table->string('avatar_url')->nullable();

            $table->enum('kyc_status', ['not_submitted', 'pending', 'verified', 'rejected'])->default('not_submitted');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('identity_verified_at')->nullable();
            $table->string('identity_type', 30)->nullable();
            $table->string('identity_number', 50)->nullable();
            $table->json('kyc_documents')->nullable();
            $table->string('kyc_rejection_reason')->nullable();

            $table->string('business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->string('cac_number', 50)->nullable();
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->unsignedInteger('review_count')->default(0);
            $table->decimal('voucher_balance', 10, 2)->default(0);

            $table->json('settings')->nullable();
            $table->string('language', 5)->default('en');
            $table->text('fcm_token')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['role', 'kyc_status']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
