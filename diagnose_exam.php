<?php
// Load Laravel
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;
use App\Models\Subject;

// Get latest JAMB exam
$jamb = ExamSession::where('exam_mode', 'jamb')
    ->latest('started_at')
    ->first();

if (!$jamb) {
    echo "No JAMB exams found.\n";
    exit;
}

$questionIds = $jamb->question_ids ?? [];
echo "Latest JAMB Exam (ID: $jamb->id)\n";
echo "Started: {$jamb->started_at}\n";
echo "Total Questions: " . count($questionIds) . "\n";
echo "Student ID: {$jamb->student_id}\n\n";

// Count questions per subject
$subjectCounts = [];
foreach ($questionIds as $qid) {
    $q = \App\Models\Question::find($qid);
    if ($q) {
        $subjectCounts[$q->subject_id][$q->subject->name] = ($subjectCounts[$q->subject_id][$q->subject->name] ?? 0) + 1;
    }
}

echo "Questions per subject:\n";
foreach ($subjectCounts as $subId => $counts) {
    foreach ($counts as $subName => $count) {
        echo "  - $subName: $count questions\n";
    }
}

// Check cache settings
echo "\nCurrent Settings:\n";
echo "  - jamb_english_questions: " . \Illuminate\Support\Facades\Cache::get('jamb_english_questions', 'DEFAULT (60)') . "\n";
echo "  - jamb_questions_per_subject: " . \Illuminate\Support\Facades\Cache::get('jamb_questions_per_subject', 'DEFAULT (40)') . "\n";

// Check total question pool per subject
echo "\nTotal questions available per subject:\n";
$subjects = Subject::withCount('questions')->orderBy('name')->get();
foreach ($subjects as $s) {
    echo "  - {$s->name}: {$s->questions_count} total\n";
}
