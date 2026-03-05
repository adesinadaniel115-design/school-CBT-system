<?php

require 'bootstrap/app.php';
$app->boot();

use App\Models\ExamSession;

$latest = ExamSession::latest()->whereNotNull('question_ids')->first();

if ($latest) {
    $count = count($latest->question_ids ?? []);
    echo "Latest exam (ID: " . $latest->id . ") has $count total questions\n";
    echo "Exam Mode: " . $latest->exam_mode . "\n";
    echo "Subject: " . $latest->subject->name . "\n";
    echo "Student ID: " . $latest->student_id . "\n";
    echo "Started: " . $latest->started_at . "\n";
} else {
    echo "No exams found\n";
}
