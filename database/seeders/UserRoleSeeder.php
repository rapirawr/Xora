<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create seller user
        if (!User::where('email', 'seller@example.com')->exists()) {
            User::create([
                'name' => 'Seller User',
                'username' => 'selleruser',
                'email' => 'seller@example.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
            ]);
        }

        // Create developer user
        if (!User::where('email', 'developer@example.com')->exists()) {
            User::create([
                'name' => 'Developer User',
                'username' => 'developeruser',
                'email' => 'developer@example.com',
                'password' => Hash::make('password'),
                'role' => 'developer',
            ]);
        }

        // Create additional seller users
        // Removed creation of additional seller users to keep only one seller account
        // User::factory()->count(3)->create([
        //     'role' => 'seller',
        // ]);

        // Create additional developer users
        // Removed creation of additional developer users to keep only one developer account
        // User::factory()->count(2)->create([
        //     'role' => 'developer',
        // ]);
    }
}
