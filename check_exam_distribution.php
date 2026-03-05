<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;

// Get latest JAMB 
$jamb = ExamSession::where('exam_mode', 'jamb')
    ->latest('started_at')
    ->first();

if (!$jamb) {
    echo "No JAMB exams found.\n";
    exit;
}

echo "Exam ID: $jamb->id\n";
echo "Subject ID (from exam_sessions.subject_id): {$jamb->subject_id}\n";
echo "Subject Name: {$jamb->subject->name}\n";
echo "Total Questions in exam: " . count($jamb->question_ids ?? []) . "\n\n";

//  Count questions by subject
$counts = [];
foreach ($jamb->question_ids ?? [] as $qid) {
    $q = \App\Models\Question::find($qid);
    if ($q) {
        $subName = $q->subject->name;
        $counts[$subName] = ($counts[$subName] ?? 0) + 1;
    }
}

echo "Questions Distribution:\n";
foreach ($counts as $subName => $count) {
    echo "  $subName: $count questions\n";
}
