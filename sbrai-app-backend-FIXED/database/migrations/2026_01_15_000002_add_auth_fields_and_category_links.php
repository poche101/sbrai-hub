<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Password reset tokens (Laravel standard table)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Add account-confirmation fields to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('confirmation_token')->nullable()->after('email_verified_at');
            $table->timestamp('account_confirmed_at')->nullable()->after('confirmation_token');
        });

        // Admin-managed listing categories link (already created in categories table)
        // Add category_id to listings for proper relational integrity
        Schema::table('listings', function (Blueprint $table) {
            $table->uuid('category_id')->nullable()->after('category');
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['confirmation_token', 'account_confirmed_at']);
        });
        Schema::dropIfExists('password_reset_tokens');
    }
};
