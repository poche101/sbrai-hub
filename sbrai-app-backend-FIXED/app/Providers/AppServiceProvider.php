<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; // 💡 Added this import
use App\Models\Category;              // 💡 Added this import

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 💡 Explicitly bind the route parameter {id} to the Category model
        Route::model('id', Category::class);
    }
}
