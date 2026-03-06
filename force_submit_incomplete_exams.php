<?php
/**
 * Force-submit all incomplete exams for students who have finished writing
 * This script allows admins to trigger submission for exams that haven't been submitted yet
 * 
 * Usage: php force_submit_incomplete_exams.php
 */

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ExamSession;
use App\Models\Question;
use App\Models\ExamAnswer;
use App\Models\ExamSubjectScore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// Get all incomplete exams (not submitted yet)
$incompleteSessions = ExamSession::whereNull('completed_at')
    ->orderBy('started_at', 'desc')
    ->get();

if ($incompleteSessions->isEmpty()) {
    echo "No incomplete exams found.\n";
    exit(0);
}

echo "Found " . $incompleteSessions->count() . " incomplete exams to submit.\n";
echo "=" . str_repeat("=", 78) . "\n";

$successCount = 0;
$errorCount = 0;

foreach ($incompleteSessions as $session) {
    try {
        echo "\nProcessing Session #{$session->id}\n";
        echo "  Student: " . $session->student->name . " ({$session->student_id})\n";
        echo "  Mode: " . strtoupper($session->exam_mode) . "\n";
        echo "  Started: " . $session->started_at->format('Y-m-d H:i:s') . "\n";

        // Get question IDs for this session
        $questionIds = array_map('intval', $session->question_ids ?? []);
        if (empty($questionIds)) {
            echo "  ERROR: No questions found for this session.\n";
            $errorCount++;
            continue;
        }

        // Load questions with shuffling applied
        $questions = Question::with('subject')->whereIn('id', $questionIds)->get();
        $shuffleOptions = Cache::get('shuffle_options', true);
        
        if ($shuffleOptions && method_exists($session, 'shuffleQuestionOptions')) {
            // This would need a helper function - for now just load them
        }
        
        $questions = $questions->keyBy('id');

        // Get all current answers from database
        $answers = $session->answers()->get()->keyBy('question_id');
        $submittedAnswers = [];
        $answeredCount = 0;

        foreach ($answers as $questionId => $answer) {
            if ($answer->selected_option) {
                $submittedAnswers[$questionId] = $answer->selected_option;
                $answeredCount++;
            } else {
                $submittedAnswers[$questionId] = null;
            }
        }

        echo "  Questions: " . $session->total_questions . " | Answered: $answeredCount\n";

        // Submit based on mode
        DB::transaction(function () use ($session, $questions, $submittedAnswers, $answeredCount) {
            if ($session->exam_mode === 'jamb') {
                submitJambExam($session, $questions, $submittedAnswers);
            } else {
                submitSchoolExam($session, $questions, $submittedAnswers);
            }
        });

        echo "  ✓ Successfully submitted. Score: " . $session->fresh()->score . "\n";
        $successCount++;

    } catch (\Exception $e) {
        echo "  ✗ ERROR: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n" . "=" . str_repeat("=", 78) . "\n";
echo "Summary:\n";
echo "  ✓ Successfully submitted: $successCount\n";
echo "  ✗ Errors: $errorCount\n";
echo "  Total: " . $incompleteSessions->count() . "\n";

/**
 * Grade a school exam
 */
function submitSchoolExam($session, $questions, $submittedAnswers)
{
    $score = 0;

    foreach ($questions as $questionId => $question) {
        $selected = strtoupper((string) ($submittedAnswers[$questionId] ?? ''));
        $selected = in_array($selected, ['A', 'B', 'C', 'D'], true) ? $selected : null;
        $isCorrect = $selected && $selected === $question->correct_option;

        if ($isCorrect) {
            $score++;
        }

        ExamAnswer::where('exam_session_id', $session->id)
            ->where('question_id', $questionId)
            ->update([
                'selected_option' => $selected,
                'is_correct' => $isCorrect,
            ]);
    }

    $session->update([
        'score' => $score,
        'completed_at' => now(),
    ]);
}

/**
 * Grade a JAMB exam
 */
function submitJambExam($session, $questions, $submittedAnswers)
{
    $subjectScores = [];

    foreach ($questions as $questionId => $question) {
        $selected = strtoupper((string) ($submittedAnswers[$questionId] ?? ''));
        $selected = in_array($selected, ['A', 'B', 'C', 'D'], true) ? $selected : null;
        $isCorrect = $selected && $selected === $question->correct_option;

        ExamAnswer::where('exam_session_id', $session->id)
            ->where('question_id', $questionId)
            ->update([
                'selected_option' => $selected,
                'is_correct' => $isCorrect,
            ]);

        $subjectId = $question->subject_id;
        if (!isset($subjectScores[$subjectId])) {
            $subjectScores[$subjectId] = [
                'correct' => 0,
                'total' => 0,
            ];
        }

        $subjectScores[$subjectId]['total']++;
        if ($isCorrect) {
            $subjectScores[$subjectId]['correct']++;
        }
    }

    // Save per-subject scores
    $totalScore = 0;

    foreach ($subjectScores as $subjectId => $scoreData) {
        $correctCount = $scoreData['correct'];
        $totalQuestions = $scoreData['total'];
        $scoreOver100 = ($correctCount / $totalQuestions) * 100;

        ExamSubjectScore::create([
            'exam_session_id' => $session->id,
            'subject_id' => $subjectId,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'score_out_of_100' => $scoreOver100,
        ]);

        $totalScore += $scoreOver100;
    }

    // Calculate average score for JAMB
    $averageScore = !empty($subjectScores) ? $totalScore / count($subjectScores) : 0;

    $session->update([
        'score' => round($averageScore, 2),
        'completed_at' => now(),
    ]);
}

echo "Done!\n";
exit(0);
