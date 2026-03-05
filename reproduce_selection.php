<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Support\Facades\Cache;

$studentId = 6;
$usedQuestionIds = ExamSession::where('student_id', $studentId)
    ->pluck('question_ids')
    ->filter()
    ->map(function ($ids) {
        return is_array($ids)
            ? $ids
            : (json_decode($ids, true) ?: []);
    })->flatten()->unique()->values()->all();

echo "Used IDs count: " . count($usedQuestionIds) . "\n";

$shuffleQuestions = Cache::get('shuffle_questions', true);

$selectQuestions = function ($subjectId, $count) use ($shuffleQuestions, $usedQuestionIds) {
    $selected = collect();

    // build a fresh query builder for the fresh selection
    $freshQ = Question::where('subject_id', $subjectId);
    if (!empty($usedQuestionIds)) {
        $freshQ->whereNotIn('id', $usedQuestionIds);
    }
    if ($shuffleQuestions) {
        $freshQ->inRandomOrder();
    }
    $fresh = $freshQ->limit($count)->get();
    $selected = $selected->merge($fresh);

    if ($selected->count() < $count) {
        $needed = $count - $selected->count();
        $exclude = $selected->pluck('id')->all();

        // build a fresh query builder for the fallback selection
        $fallbackQ = Question::where('subject_id', $subjectId);
        if (!empty($exclude)) {
            $fallbackQ->whereNotIn('id', $exclude);
        }
        if ($shuffleQuestions) {
            $fallbackQ->inRandomOrder();
        }
        $fallback = $fallbackQ->limit($needed)->get();
        $selected = $selected->merge($fallback);
    }

    return $selected->values();
};

$englishSubject = Subject::where('name','LIKE','%English%')->first();
$englishCount = Cache::get('jamb_english_questions', 60);
$subjectCount = Cache::get('jamb_questions_per_subject', 40);

if (!$englishSubject) {
    echo "English subject not found\n";
    exit(1);
}

$englishQ = $selectQuestions($englishSubject->id, $englishCount);
echo "englishSubject id: {$englishSubject->id}\n";
echo "englishQ count: " . $englishQ->count() . "\n";

$subjectIds = [13,13,13]; // simulate the 3 selected subjects from exam
foreach ($subjectIds as $id) {
    $q = $selectQuestions($id, $subjectCount);
    echo "subject $id count: " . $q->count() . "\n";
}

