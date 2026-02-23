<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'is_admin' => false
        ]);

        $this->command->info('Student account created!');
        $this->command->info('Email: student@test.com');
        $this->command->info('Password: password');
    }
}
