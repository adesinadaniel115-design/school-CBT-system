<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use App\Models\Question;

echo "=== COMPREHENSIVE HEALTH CHECK ===\n\n";

// 1. Check subject counts
echo "1. SUBJECT POPULATION CHECK:\n";
echo "================================\n";
$subjects = Subject::all();
$totalQuestions = 0;
$subjectStats = [];

foreach($subjects as $s) {
    $count = Question::where('subject_id', $s->id)->count();
    $totalQuestions += $count;
    $subjectStats[] = [
        'name' => $s->name,
        'count' => $count
    ];
    echo sprintf("%-35s %5d questions\n", $s->name . ":", $count);
}

echo sprintf("%-35s %5d questions\n", "TOTAL:", $totalQuestions);
echo "\n";

// 2. Verify correct_option values
echo "2. CORRECT ANSWER FORMAT CHECK:\n";
echo "================================\n";
$invalidAnswers = Question::whereNotIn('correct_option', ['A', 'B', 'C', 'D'])->get();
if($invalidAnswers->count() === 0) {
    echo "✓ All correct options are valid (A, B, C, or D)\n\n";
} else {
    echo "✗ FOUND " . $invalidAnswers->count() . " INVALID CORRECT OPTIONS:\n";
    foreach($invalidAnswers as $q) {
        echo "  - Q: " . substr($q->question_text, 0, 50) . "... Answer: " . $q->correct_option . "\n";
    }
    echo "\n";
}

// 3. Check for orphaned options
echo "3. QUESTION STRUCTURE CHECK:\n";
echo "================================\n";
$questionsWithMissingOptions = Question::where(function($q) {
    return $q->whereNull('option_a')
        ->orWhereNull('option_b')
        ->orWhereNull('option_c')
        ->orWhereNull('option_d')
        ->orWhereNull('explanation');
})->get();

if($questionsWithMissingOptions->count() === 0) {
    echo "✓ All questions have all 4 options and explanations\n\n";
} else {
    echo "✗ FOUND " . $questionsWithMissingOptions->count() . " INCOMPLETE QUESTIONS\n";
    foreach($questionsWithMissingOptions as $q) {
        echo "  Q: " . substr($q->question_text, 0, 50) . "...\n";
    }
    echo "\n";
}

// 4. Check difficulty levels
echo "4. DIFFICULTY LEVEL CHECK:\n";
echo "================================\n";
$difficultyBreakdown = Question::groupBy('difficulty_level')->selectRaw('difficulty_level, count(*) as count')->get();
foreach($difficultyBreakdown as $d) {
    echo sprintf("  %-10s: %3d questions\n", ucfirst($d->difficulty_level), $d->count);
}
echo "\n";

// 5. Check for duplicate questions within subjects
echo "5. DUPLICATE DETECTION (within subjects):\n";
echo "================================\n";
$duplicateCheckQuery = "
    SELECT subject_id, question_text, COUNT(*) as count 
    FROM questions 
    GROUP BY subject_id, question_text 
    HAVING COUNT(*) > 1
";
$PDO = DB::connection()->getPdo();
$duplicates = $PDO->query($duplicateCheckQuery)->fetchAll(PDO::FETCH_ASSOC);

if(empty($duplicates)) {
    echo "✓ No duplicate questions within subjects\n\n";
} else {
    echo "✗ FOUND " . count($duplicates) . " DUPLICATE QUESTION(S):\n";
    foreach($duplicates as $dup) {
        $subjectName = Subject::find($dup['subject_id'])->name;
        echo "  Subject: $subjectName\n";
        echo "  Question: " . substr($dup['question_text'], 0, 60) . "...\n";
        echo "  Count: " . $dup['count'] . "\n\n";
    }
}

// 6. Sample data validation - check last 10 questions
echo "6. SAMPLE DATA VALIDATION (Last 10 questions):\n";
echo "================================\n";
$recentQuestions = Question::latest()->take(10)->get();
$sampleValid = true;

foreach($recentQuestions as $q) {
    $isValid = !empty($q->question_text) 
        && !empty($q->option_a)
        && !empty($q->option_b)
        && !empty($q->option_c)
        && !empty($q->option_d)
        && in_array($q->correct_option, ['A', 'B', 'C', 'D'])
        && !empty($q->explanation)
        && in_array($q->difficulty_level, ['easy', 'medium', 'hard']);
    
    $status = $isValid ? "✓" : "✗";
    echo sprintf("%s Q: %s... [%s]\n", $status, substr($q->question_text, 0, 45), $q->Subject->name);
    
    if(!$isValid) {
        $sampleValid = false;
        echo "  Issues: ";
        if(empty($q->question_text)) echo "empty question ";
        if(empty($q->option_a) || empty($q->option_b) || empty($q->option_c) || empty($q->option_d)) echo "missing options ";
        if(!in_array($q->correct_option, ['A', 'B', 'C', 'D'])) echo "invalid correct_option ";
        if(empty($q->explanation)) echo "missing explanation ";
        echo "\n";
    }
}

if($sampleValid) {
    echo "\n✓ All sample questions are properly formatted\n\n";
}

// 7. Subject breakdown by difficulty
echo "7. DIFFICULTY DISTRIBUTION BY SUBJECT:\n";
echo "================================\n";
foreach($subjectStats as $subj) {
    if($subj['count'] > 0) {
        $diffBySubj = Question::where('subject_id', function($q) use ($subj) {
            $q->whereRelation('Subject', 'name', $subj['name']);
        })->groupBy('difficulty_level')->selectRaw('difficulty_level, count(*) as count')->get();
        
        echo $subj['name'] . " (" . $subj['count'] . " total):\n";
        
        $easyCount = Question::whereHas('Subject', function($q) use ($subj) {
            $q->where('name', $subj['name']);
        })->where('difficulty_level', 'easy')->count();
        
        $mediumCount = Question::whereHas('Subject', function($q) use ($subj) {
            $q->where('name', $subj['name']);
        })->where('difficulty_level', 'medium')->count();
        
        $hardCount = Question::whereHas('Subject', function($q) use ($subj) {
            $q->where('name', $subj['name']);
        })->where('difficulty_level', 'hard')->count();
        
        echo sprintf("  Easy: %2d  |  Medium: %2d  |  Hard: %2d\n", $easyCount, $mediumCount, $hardCount);
    }
}

echo "\n";

// 8. Final summary
echo "8. FINAL SUMMARY:\n";
echo "================================\n";
echo "Total Questions in System: $totalQuestions\n";
echo "Total Subjects: " . $subjects->count() . "\n";
echo "Average Questions per Subject: " . round($totalQuestions / $subjects->count(), 1) . "\n";
echo "Subjects with 0 questions: " . collect($subjectStats)->filter(fn($s) => $s['count'] === 0)->count() . "\n\n";

if($invalidAnswers->count() === 0 && empty($duplicates) && $sampleValid) {
    echo "✓ HEALTH CHECK PASSED - System is ready for production\n";
} else {
    echo "✗ HEALTH CHECK FAILED - Please fix issues before deployment\n";
}

echo "\n";
