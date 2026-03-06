<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plan;

$premium = Plan::where('name', 'PREMIUM PLAN')->first();

if ($premium) {
    echo "Plan: {$premium->name}\n";
    echo "ID: {$premium->id}\n";
    echo "attempts_allowed: {$premium->attempts_allowed}\n";
    echo "school_questions: {$premium->school_questions}\n";
    echo "jamb_questions_per_subject: {$premium->jamb_questions_per_subject}\n";
    echo "jamb_english_questions: {$premium->jamb_english_questions}\n";
} else {
    echo "PREMIUM PLAN not found\n";
}
