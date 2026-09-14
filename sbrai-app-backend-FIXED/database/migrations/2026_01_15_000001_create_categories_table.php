<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('listing_type', ['product', 'service', 'property'])->default('product');
            $table->string('icon')->nullable();          // emoji or icon class
            $table->string('image_url')->nullable();      // category thumbnail
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->uuid('created_by')->nullable();       // admin who created it
            $table->timestamps();
            $table->softDeletes();

            $table->index('listing_type');
            $table->index('is_active');
        });

        // Seed initial categories matching the existing UI
        $defaults = [
            ['name' => 'Sharp Sand',  'slug' => 'sharp-sand',  'listing_type' => 'product',  'icon' => '🏖️', 'sort_order' => 1],
            ['name' => 'Granite',     'slug' => 'granite',     'listing_type' => 'product',  'icon' => '🪨', 'sort_order' => 2],
            ['name' => 'Blocks',      'slug' => 'blocks',      'listing_type' => 'product',  'icon' => '🧱', 'sort_order' => 3],
            ['name' => 'Cement',      'slug' => 'cement',      'listing_type' => 'product',  'icon' => '🏭', 'sort_order' => 4],
            ['name' => 'Iron Rods',   'slug' => 'iron-rods',   'listing_type' => 'product',  'icon' => '⚙️', 'sort_order' => 5],
            ['name' => 'Paints',      'slug' => 'paints',      'listing_type' => 'product',  'icon' => '🎨', 'sort_order' => 6],
            ['name' => 'Furniture',   'slug' => 'furniture',   'listing_type' => 'product',  'icon' => '🛋️', 'sort_order' => 7],
            ['name' => 'Scaffolding', 'slug' => 'scaffolding', 'listing_type' => 'product',  'icon' => '🏗️', 'sort_order' => 8],
            ['name' => 'Logistics',   'slug' => 'logistics',   'listing_type' => 'service',  'icon' => '🚛', 'sort_order' => 9],
            ['name' => 'Borehole',    'slug' => 'borehole',    'listing_type' => 'service',  'icon' => '💧', 'sort_order' => 10],
            ['name' => 'Cleaning',    'slug' => 'cleaning',    'listing_type' => 'service',  'icon' => '🧹', 'sort_order' => 11],
            ['name' => 'Fumigation',  'slug' => 'fumigation',  'listing_type' => 'service',  'icon' => '🔬', 'sort_order' => 12],
            ['name' => 'Apartments',  'slug' => 'apartments',  'listing_type' => 'property', 'icon' => '🏢', 'sort_order' => 13],
            ['name' => 'Houses',      'slug' => 'houses',      'listing_type' => 'property', 'icon' => '🏡', 'sort_order' => 14],
            ['name' => 'Commercial',  'slug' => 'commercial',  'listing_type' => 'property', 'icon' => '🏬', 'sort_order' => 15],
            ['name' => 'Land',        'slug' => 'land',        'listing_type' => 'property', 'icon' => '🌿', 'sort_order' => 16],
        ];

        foreach ($defaults as $cat) {
            DB::table('categories')->insert(array_merge($cat, [
                'id'         => (string) \Illuminate\Support\Str::uuid(),
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
