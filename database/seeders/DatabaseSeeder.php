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
    }
}
