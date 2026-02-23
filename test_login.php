<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== DATABASE VERIFICATION ===\n\n";

echo "Total Users: " . User::count() . "\n";
echo "Total Subjects: " . \App\Models\Subject::count() . "\n";
echo "Total Questions: " . \App\Models\Question::count() . "\n\n";

echo "=== EXISTING USERS ===\n";
User::all()->each(function($user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Admin: " . ($user->is_admin ? 'Yes' : 'No') . "\n";
});

echo "\n=== TEST LOGIN ===\n";
echo "Testing: admin@schoolcbt.com / admin123\n";
if (Hash::check('admin123', User::where('email', 'admin@schoolcbt.com')->first()?->password ?? '')) {
    echo "✓ Password hash matches!\n";
} else {
    echo "✗ Password hash DOES NOT match\n";
}

echo "\nTesting: student@test.com / password\n";
if (Hash::check('password', User::where('email', 'student@test.com')->first()?->password ?? '')) {
    echo "✓ Password hash matches!\n";
} else {
    echo "✗ Password hash DOES NOT match\n";
}
