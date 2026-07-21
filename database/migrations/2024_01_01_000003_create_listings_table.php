<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Imported DB facade

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            // 1. Removed DB::raw UUID generation for database flexibility
            $table->uuid('id')->primary();
            $table->uuid('vendor_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 12, 2)->default(0);
            $table->string('price_unit', 100);
            $table->string('category', 100);
            $table->enum('type', ['product', 'service', 'property'])->default('product');
            $table->enum('status', ['draft', 'active', 'sold', 'removed', 'pending'])->default('active');
            $table->string('location', 255);
            $table->string('state', 100);
            $table->json('image_urls')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->json('attributes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['status', 'category']);
            $table->index(['status', 'state']);
            $table->index(['vendor_id', 'status']);

            // 2. Conditionally apply fulltext index to bypass SQLite restrictions
            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['title', 'description']);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
