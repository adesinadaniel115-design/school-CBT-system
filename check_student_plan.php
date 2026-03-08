<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;
use App\Models\User;

// Get the student who took the latest JAMB exam
$jamb = ExamSession::where('exam_mode', 'jamb')
    ->latest('started_at')
    ->first();

if (!$jamb) {
    echo "No JAMB exams found.\n";
    exit;
}

$student = User::find($jamb->student_id);
echo "Student ID: {$student->id}\n";
echo "Name: {$student->name}\n";

$activePlan = $student->activePlan();
if ($activePlan) {
    echo "\nActive Plan Found!\n";
    echo "Plan Name: {$activePlan->name}\n";
    echo "JAMB English Questions: {$activePlan->jamb_english_questions}\n";
    echo "JAMB Per Subject: {$activePlan->jamb_questions_per_subject}\n";
} else {
    echo "\nNo active plan\n";
}

// Check all student plans
$plans = $student->studentPlans()->with('plan')->get();
echo "\nAll Student Plans:\n";
foreach ($plans as $sp) {
    echo "Plan: {$sp->plan->name}\n";
    echo "  Attempts Remaining: {$sp->attempts_remaining}\n";
    echo "  Expires: {$sp->expires_at}\n";
    echo "  Questions Per Subject: {$sp->plan->jamb_questions_per_subject}\n";
}
