<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Removed test user creation to keep only developer and seller accounts
        // if (!User::where('email', 'test@example.com')->exists()) {
        //     User::factory()->create([
        //         'name' => 'Test User',
        //         'username' => 'testuser',
        //         'email' => 'test@example.com',
        //     ]);
        // }

        // Call user role seeder for seller and developer
        $this->call([
            UserRoleSeeder::class,
            // Removed RatingSeeder to allow only verified buyer ratings
            // RatingSeeder::class,
            // Removed ProductSeeder to disable product seeding
            // ProductSeeder::class,
            // Removed MessageSeeder::class,
        ]);
    }
}
