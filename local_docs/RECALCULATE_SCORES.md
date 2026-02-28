# Recalculate Exam Scores Script

This script fixes scores for students who took exams when the shuffle bug was present.

## The Script

Create the file `recalculate_scores.php` in the project root:

```php
<?php

/**
 * Score Recalculation Script
 *
 * Fixes scores for exams affected by the shuffle bug where answers were
 * validated against unshuffled correct_option instead of shuffled one.
 *
 * Run with: php recalculate_scores.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;
use App\Models\ExamAnswer;
use App\Models\ExamSubjectScore;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

echo "===========================================\n";
echo "  SCORE RECALCULATION SCRIPT\n";
echo "===========================================\n\n";

/**
 * Apply shuffle to get the correct shuffled correct_option
 * (Same logic as ExamController::shuffleQuestionOptions)
 */
function getShuffledCorrectOption($question, $sessionId) {
    // Use session ID + question ID as seed for consistent shuffling
    mt_srand($sessionId + $question->id);

    // Shuffle while preserving keys
    $keys = ['A', 'B', 'C', 'D'];
    shuffle($keys);

    // Map old position to new position
    $mapping = [];
    $newLabels = ['A', 'B', 'C', 'D'];
    foreach ($keys as $index => $originalKey) {
        $newLabel = $newLabels[$index];
        $mapping[$originalKey] = $newLabel;
    }

    // Reset random seed
    mt_srand();

    // Return the shuffled correct option
    return $mapping[$question->correct_option];
}

// Get all completed exam sessions
$sessions = ExamSession::whereNotNull('completed_at')->get();

echo "Found " . $sessions->count() . " completed exam sessions to process.\n\n";

$totalSessionsUpdated = 0;
$totalAnswersFixed = 0;

foreach ($sessions as $session) {
    echo "Processing Session #{$session->id} (Student #{$session->student_id}, Mode: {$session->exam_mode})...\n";

    $questionIds = array_map('intval', $session->question_ids ?? []);
    if (empty($questionIds)) {
        echo "  - Skipping: No questions in session\n";
        continue;
    }

    $questions = Question::whereIn('id', $questionIds)->get()->keyBy('id');
    $answers = ExamAnswer::where('exam_session_id', $session->id)->get();

    $oldScore = $session->score;
    $newScore = 0;
    $answersChanged = 0;
    $subjectScores = []; // For JAMB mode

    DB::beginTransaction();

    try {
        foreach ($answers as $answer) {
            $question = $questions->get($answer->question_id);
            if (!$question) {
                continue;
            }

            // Get the shuffled correct option
            $shuffledCorrectOption = getShuffledCorrectOption($question, $session->id);

            // Recalculate is_correct
            $wasCorrect = $answer->is_correct;
            $isCorrect = $answer->selected_option
                ? $answer->selected_option === $shuffledCorrectOption
                : false;

            // Track subject scores for JAMB mode
            $subjectId = $question->subject_id;
            if (!isset($subjectScores[$subjectId])) {
                $subjectScores[$subjectId] = ['correct' => 0, 'total' => 0];
            }
            $subjectScores[$subjectId]['total']++;
            if ($isCorrect) {
                $subjectScores[$subjectId]['correct']++;
                $newScore++;
            }

            // Update if changed
            if ($wasCorrect !== $isCorrect) {
                $answer->is_correct = $isCorrect;
                $answer->save();
                $answersChanged++;
                $totalAnswersFixed++;
            }
        }

        // Update session score
        $session->score = $newScore;
        $session->save();

        // Update subject scores for JAMB mode
        if ($session->exam_mode === 'jamb') {
            foreach ($subjectScores as $subjectId => $scores) {
                $totalForSubject = $scores['total'];
                $correctForSubject = $scores['correct'];

                // Calculate JAMB-style score (400 points total, divided among 4 subjects)
                $maxPerSubject = 100; // Assuming equal distribution
                $scoreForSubject = $totalForSubject > 0
                    ? round(($correctForSubject / $totalForSubject) * $maxPerSubject)
                    : 0;

                ExamSubjectScore::updateOrCreate(
                    [
                        'exam_session_id' => $session->id,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'correct_count' => $correctForSubject,
                        'total_questions' => $totalForSubject,
                        'score' => $scoreForSubject,
                    ]
                );
            }

            // Update total JAMB score
            $totalJambScore = ExamSubjectScore::where('exam_session_id', $session->id)->sum('score');
            $session->score = $totalJambScore;
            $session->save();
            $newScore = $totalJambScore;
        }

        DB::commit();

        if ($oldScore !== $newScore || $answersChanged > 0) {
            echo "  - Score: {$oldScore} -> {$newScore} ({$answersChanged} answers corrected)\n";
            $totalSessionsUpdated++;
        } else {
            echo "  - No changes needed\n";
        }

    } catch (\Exception $e) {
        DB::rollBack();
        echo "  - ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\n===========================================\n";
echo "  SUMMARY\n";
echo "===========================================\n";
echo "Sessions processed: " . $sessions->count() . "\n";
echo "Sessions updated: {$totalSessionsUpdated}\n";
echo "Answers corrected: {$totalAnswersFixed}\n";
echo "\nDone!\n";
```

## How to Execute

### Option 1: Direct Execution (Recommended)

```bash
cd /path/to/school-CBT-system
php recalculate_scores.php
```

### Option 2: Using Artisan Tinker

```bash
php artisan tinker
```

Then paste the script contents (without the `require` and bootstrap lines).

## What the Script Does

1. **Loads all completed exam sessions** from the database
2. **For each session:**
    - Retrieves all questions and answers
    - Applies the same shuffle algorithm (seeded by `sessionId + questionId`) to determine what the shuffled `correct_option` was
    - Recalculates `is_correct` for each answer
    - Updates the answer record if the value changed
    - Recalculates the total score
    - For JAMB mode: Also recalculates per-subject scores
3. **Outputs a summary** showing how many sessions and answers were corrected

## Before Running

1. **Backup your database** - Always recommended before bulk updates

    ```bash
    # For MySQL
    mysqldump -u username -p database_name > backup_before_recalc.sql

    # For SQLite
    cp database/database.sqlite database/database_backup.sqlite
    ```

2. **Test on a single session first** (optional):
   Modify the query to test on one session:
    ```php
    $sessions = ExamSession::whereNotNull('completed_at')->limit(1)->get();
    ```

## Expected Output

```
===========================================
  SCORE RECALCULATION SCRIPT
===========================================

Found 11 completed exam sessions to process.

Processing Session #1 (Student #5, Mode: school)...
  - Score: 15 -> 23 (12 answers corrected)
Processing Session #2 (Student #7, Mode: jamb)...
  - Score: 180 -> 245 (8 answers corrected)
Processing Session #3 (Student #8, Mode: school)...
  - No changes needed
...

===========================================
  SUMMARY
===========================================
Sessions processed: 11
Sessions updated: 9
Answers corrected: 47

Done!
```

## Rollback

If you need to rollback, restore your database backup:

```bash
# For MySQL
mysql -u username -p database_name < backup_before_recalc.sql

# For SQLite
cp database/database_backup.sqlite database/database.sqlite
```
