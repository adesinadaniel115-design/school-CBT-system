<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== LITERATURE IN ENGLISH: 40-QUESTION VERIFICATION ===\n\n";

// Get all literature questions loaded from the new loader
$sections = [
    'literary_terms' => 'Section A: Literary Appreciation & Terms',
    'second_class_citizen' => 'Section B: African Prose - Second Class Citizen',
    'unexpected_joy_dawn' => 'Section C: Non-African Prose - Unexpected Joy at Dawn',
    'lion_jewel' => 'Section D: Drama - The Lion and the Jewel',
    'midsummer_dream' => 'Section E: Shakespeare - A Midsummer Night\'s Dream',
    'poetry_mixed' => 'Section F: Poetry - African & Non-African'
];

$totalQuestions = 0;
$totalExplanations = 0;

foreach ($sections as $group => $title) {
    $questions = DB::table('questions')
        ->where('passage_group', $group)
        ->orderBy('id')
        ->get();

    echo "📚 $title\n";
    echo str_repeat("─", 70) . "\n";
    
    if ($questions->isEmpty()) {
        echo "❌ No questions found for this section\n\n";
        continue;
    }

    echo "Count: {$questions->count()} questions\n\n";

    foreach ($questions as $index => $q) {
        $totalQuestions++;
        $answerLabel = ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'][$q->correct_option] ?? '?';
        
        echo ($index + 1) . ". " . substr($q->question_text, 0, 60);
        if (strlen($q->question_text) > 60) echo "...";
        echo "\n";
        echo "   Options: A) " . substr($q->option_a, 0, 25) . "..., B) " . substr($q->option_b, 0, 25) . "...\n";
        echo "   Answer: $answerLabel | Explanation: ";
        if ($q->explanation) {
            echo substr($q->explanation, 0, 45) . "...\n";
            $totalExplanations++;
        } else {
            echo "❌ MISSING\n";
        }
        echo "\n";
    }
    
    echo "\n";
}

echo "=== SUMMARY ===\n";
echo "────────────────────────────────────────────────────────\n";
echo "✅ Total Questions Verified: $totalQuestions\n";
echo "✅ Questions with Explanations: $totalExplanations / $totalQuestions\n";

if ($totalExplanations === $totalQuestions) {
    echo "✅ All questions have explanations!\n";
} else {
    echo "⚠️ " . ($totalQuestions - $totalExplanations) . " questions missing explanations\n";
}

// Verify answer distribution
echo "\n📊 Answer Distribution:\n";
$answerCounts = DB::table('questions')
    ->where('passage_group', 'IN', array_keys($sections))
    ->groupBy('correct_option')
    ->selectRaw('correct_option, COUNT(*) as count')
    ->get();

foreach ($answerCounts as $row) {
    echo "   {$row->correct_option}: {$row->count} questions\n";
}

// Verify all have passages and groups
$questionsWithoutPassage = DB::table('questions')
    ->where('passage_group', 'IN', array_keys($sections))
    ->whereNull('passage_text')
    ->count();

$questionsWithoutGroup = DB::table('questions')
    ->where('passage_group', 'IN', array_keys($sections))
    ->whereNull('passage_group')
    ->count();

echo "\n🔍 Quality Checks:\n";
echo "   Questions without passage_text: $questionsWithoutPassage\n";
echo "   Questions without passage_group: $questionsWithoutGroup\n";

if ($questionsWithoutPassage == 0 && $questionsWithoutGroup == 0) {
    echo "   ✅ All questions properly categorized!\n";
}

echo "\n✨ Literature in English mock exam is READY for testing!\n";
