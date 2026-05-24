<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specific admin/test user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@edumind.com',
            'password' => Hash::make('password'),
        ]);

        // Create 10 random users
        User::factory(10)->create();
    }
}
