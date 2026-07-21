<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuid('buyer_id');
            $table->uuid('vendor_id');
            $table->uuid('listing_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['buyer_id', 'vendor_id', 'listing_id']);
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('set null');
            $table->index(['buyer_id', 'updated_at']);
            $table->index(['vendor_id', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
