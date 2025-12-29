<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@smkyasmu.com'],
            [
                'name' => 'Admin SMK Yasmu',
                'password' => Hash::make('password'), // Default password for admin
                'is_admin' => true,
            ]
        );

        // Regular User (optional, for testing non-admin access)
        User::updateOrCreate(
            ['email' => 'user@smkyasmu.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('password'), // Default password for regular user
                'is_admin' => false,
            ]
        );

        // You can also create more users using the factory
        // User::factory(10)->create();
    }
}