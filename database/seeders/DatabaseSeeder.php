<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Imported for password hashing

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Default Admin Account
        User::create([
            'full_name'    => 'Sbrai Administrator',
            'email'        => 'admin@sbrai.com',
            'password'     => Hash::make('admin1234'), // Always hash passwords!
            'role'         => 'admin',
            'kyc_status'   => 'verified',
            'is_verified'  => true,
        ]);

        // 2. Your Standard Test User (Fixed column name to full_name)
        User::factory()->create([
            'full_name' => 'Test User',
            'email'     => 'test@example.com',
            'role'      => 'buyer',
        ]);

        // 3. A verified, subscribed vendor so /post-ad has an account that
        // can actually publish immediately (kyc_status + active subscription
        // are both required by CanPostListingMiddleware).
        $vendor = User::factory()->create([
            'full_name'   => 'Demo Vendor',
            'email'       => 'vendor@example.com',
            'role'        => 'vendor',
            'kyc_status'  => 'verified',
            'is_verified' => true,
        ]);
        $vendor->subscriptions()->create([
            'status'          => 'active',
            'start_date'      => now(),
            'end_date'        => now()->addYear(),
            'amount_paid'     => 20000,
            'payment_method'  => 'paystack',
            'payment_gateway' => 'paystack',
        ]);

        // Categories are already seeded directly inside the
        // create_categories_table migration (Sharp Sand, Cement, Blocks,
        // Logistics, Apartments, etc.) — no separate seeder needed.
    }
}
