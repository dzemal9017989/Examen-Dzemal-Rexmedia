<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
public function run(): void
{
    // This makes a test user and checks if the user already exists
    // If the user does not exist, it creates a new user with the specified details
    if (!User::where('email', 'test@example.com')->exists()) {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role' => 'user',
        ]);
    }

    // This controlls if the admin user already exists
    if (!User::where('email', 'admin@example.com')->exists()) {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role' => 'admin',
        ]);
    }

    // This executes the StatusSeeder to populate the statuses table
    $this->call(StatusSeeder::class);
}

}
