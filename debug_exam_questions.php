<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;
use App\Models\Subject;
use Illuminate\Support\Facades\Cache;

$jamb = ExamSession::where('exam_mode', 'jamb')
    ->latest('started_at')
    ->where('id', '>', 50)  // Recent exams
    ->first();

if (!$jamb) {
    echo "No recent JAMB exams found.\n";
    exit;
}

echo "=== Exam ID: $jamb->id ===\n";
echo "Total Questions in DB: " . count($jamb->question_ids ?? []) . "\n";
echo "\n=== Current Cache Settings ===\n";
echo "jamb_english_questions: " . (Cache::get('jamb_english_questions') ?? 60) . "\n";
echo "jamb_questions_per_subject: " . (Cache::get('jamb_questions_per_subject') ?? 40) . "\n";

// Try displaying just the first few question IDs
$questionIds = array_slice($jamb->question_ids ?? [], 0, 10);
echo "\n=== First 10 Question IDs ===\n";
foreach ($questionIds as $i => $qid) {
    $q = \App\Models\Question::find($qid);
    if ($q) {
        echo "Q$i: ID $qid from {$q->subject->name}\n";
    }
}
