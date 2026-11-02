<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_views', function (Blueprint $table) {
            $table->id();
            $table->char('listing_id', 36);
            $table->date('date');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            // One row per listing per day; views are incremented in place.
            $table->unique(['listing_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_views');
    }
};
