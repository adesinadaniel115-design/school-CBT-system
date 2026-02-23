<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@schoolcbt.com')->first();
if ($user) {
    $user->password = Hash::make('admin123');
    $user->save();
    echo "✓ Admin password has been reset to: admin123\n";
} else {
    echo "✗ Admin user not found\n";
}

// Also fix student password
$student = User::where('email', 'student@test.com')->first();
if ($student) {
    $student->password = Hash::make('password');
    $student->save();
    echo "✓ Student password has been reset to: password\n";
} else {
    echo "✗ Student user not found\n";
}
