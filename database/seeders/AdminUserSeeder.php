<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@schoolcbt.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        echo "Admin user created/verified:\n";
        echo "Email: admin@schoolcbt.com\n";
        echo "Password: admin123\n";
    }
}
