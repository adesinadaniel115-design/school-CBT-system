<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;

$jamb = ExamSession::where('exam_mode', 'jamb')
    ->latest('started_at')
    ->first();

if (!$jamb) {
    echo "No JAMB exams found.\n";
    exit;
}

// Check the question_ids data type and content
echo "Exam ID: $jamb->id\n";
echo "question_ids type: " . gettype($jamb->question_ids) . "\n";
echo "question_ids value:\n";
var_dump($jamb->question_ids);
