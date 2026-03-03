<?php
/**
 * Fix Duplicate Questions
 * Removes duplicate question_text entries within each subject
 * Keeps first occurrence, deletes subsequent occurrences
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Question;
use App\Models\Subject;

echo "=== DUPLICATE REMOVAL SCRIPT ===\n\n";

try {
    $duplicateCount = 0;
    $deletedCount = 0;
    
    // Get all subjects
    $subjects = Subject::all();
    
    foreach ($subjects as $subject) {
        echo "Checking {$subject->name}...\n";
        
        // Find duplicate question_text within this subject
        $duplicates = Question::where('subject_id', $subject->id)
            ->select('question_text')
            ->groupBy('question_text')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('question_text')
            ->toArray();
        
        if (count($duplicates) > 0) {
            echo "  Found " . count($duplicates) . " duplicate(s) in this subject\n";
            $duplicateCount += count($duplicates);
            
            foreach ($duplicates as $questionText) {
                // Get all questions with this text (ordered by ID to keep first)
                $questions = Question::where('subject_id', $subject->id)
                    ->where('question_text', $questionText)
                    ->orderBy('id', 'asc')
                    ->get();
                
                if ($questions->count() > 1) {
                    // Keep first, delete rest
                    for ($i = 1; $i < $questions->count(); $i++) {
                        echo "    Deleting duplicate: " . substr($questionText, 0, 50) . "...\n";
                        $questions[$i]->delete();
                        $deletedCount++;
                    }
                }
            }
        } else {
            echo "  ✓ No duplicates found\n";
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Total duplicate question texts found: {$duplicateCount}\n";
    echo "Total duplicate records deleted: {$deletedCount}\n";
    
    // Final count
    $totalQuestions = Question::count();
    echo "\nFinal total questions: {$totalQuestions}\n";
    
    // Show subject counts after cleanup
    echo "\n=== FINAL SUBJECT COUNTS ===\n";
    $subjects = Subject::withCount('questions')->orderBy('name')->get();
    foreach ($subjects as $subject) {
        echo "{$subject->name}: {$subject->questions_count} questions\n";
    }
    
    $finalTotal = 0;
    foreach ($subjects as $subject) {
        $finalTotal += $subject->questions_count;
    }
    echo "---\nTOTAL: {$finalTotal} questions\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
