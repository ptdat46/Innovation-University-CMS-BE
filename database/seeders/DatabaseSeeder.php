<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users with different roles
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@innovation.edu',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Test Writer',
            'email' => 'writer@innovation.edu',
            'role' => 'writer',
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@innovation.edu',
            'role' => 'user',
        ]);

        // Create additional writers for posts
        User::factory()->count(3)->create(['role' => 'writer']);

        // Seed posts
        $this->call([
            PostSeeder::class,
        ]);
    }
}
