<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;
use App\Models\Question;

$jamb = ExamSession::where('exam_mode', 'jamb')
    ->latest('started_at')
    ->first();

if (!$jamb) {
    echo "No JAMB exams found.\n";
    exit;
}

echo "Exam ID: $jamb->id\n";
echo "Question IDs array: " . implode(',', array_slice($jamb->question_ids ?? [], 0, 10)) . " ...\n\n";

// Get all 30 questions with their subjects
$allQs = Question::whereIn('id', $jamb->question_ids ?? [])->with('subject')->get();
$counts = $allQs->groupBy('subject_id')->map->count();

echo "Questions by Subject ID:\n";
foreach ($counts as $subId => $count) {
    $sub = \App\Models\Subject::find($subId);
    echo "  Subject ID $subId ({$sub->name}): $count questions\n";
}
