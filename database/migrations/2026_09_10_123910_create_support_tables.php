<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable(); // null for guest visitors
            $table->string('guest_email')->nullable();
            $table->string('guest_token')->nullable()->index(); // ties an anonymous browser session to its conversation
            $table->enum('status', ['open', 'escalated', 'resolved'])->default('open');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('conversation_id');
            $table->enum('sender', ['visitor', 'ai', 'agent'])->default('visitor');
            $table->text('body');
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('support_conversations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_conversations');
    }
};
