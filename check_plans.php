<?php
// Minimal Laravel bootstrap
require 'vendor/autoload.php';
$app = new Illuminate\Foundation\Application(getcwd());
$app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_DATABASE', 'cbt'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
]);
$db->setAsGlobal();
$db->bootEloquent();

// Get database connection
try {
    $plans = DB::table('plans')->get();
    echo "Plans:\n";
    foreach ($plans as $plan) {
        echo "ID: {$plan->id}, Name: {$plan->name}, JAMB Per Subject: {$plan->jamb_questions_per_subject}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
