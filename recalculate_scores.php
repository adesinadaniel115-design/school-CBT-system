<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ExamSession;
use App\Models\ExamAnswer;
use App\Models\ExamSubjectScore;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

echo "===========================================\n";
echo "  SCORE RECALCULATION SCRIPT\n";
echo "===========================================\n\n";

function getShuffledCorrectOption($question, $sessionId) {
    mt_srand($sessionId + $question->id);
    $keys = ['A', 'B', 'C', 'D'];
    shuffle($keys);
    $mapping = [];
    $newLabels = ['A', 'B', 'C', 'D'];
    foreach ($keys as $index => $originalKey) {
        $mapping[$originalKey] = $newLabels[$index];
    }
    mt_srand();
    return $mapping[$question->correct_option];
}

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
    $subjectScores = [];

    DB::beginTransaction();

    try {
        foreach ($answers as $answer) {
            $question = $questions->get($answer->question_id);
            if (!$question) continue;

            $shuffledCorrectOption = getShuffledCorrectOption($question, $session->id);

            $wasCorrect = $answer->is_correct;
            $isCorrect = $answer->selected_option
                ? $answer->selected_option === $shuffledCorrectOption
                : false;

            $subjectId = $question->subject_id;
            if (!isset($subjectScores[$subjectId])) {
                $subjectScores[$subjectId] = ['correct' => 0, 'total' => 0];
            }
            $subjectScores[$subjectId]['total']++;
            if ($isCorrect) {
                $subjectScores[$subjectId]['correct']++;
                $newScore++;
            }

            if ($wasCorrect !== $isCorrect) {
                $answer->is_correct = $isCorrect;
                $answer->save();
                $answersChanged++;
                $totalAnswersFixed++;
            }
        }

        $session->score = $newScore;
        $session->save();

        if ($session->exam_mode === 'jamb') {
            foreach ($subjectScores as $subjectId => $scores) {
                $totalForSubject = $scores['total'];
                $correctForSubject = $scores['correct'];

                $scoreForSubject = $totalForSubject > 0
                    ? round(($correctForSubject / $totalForSubject) * 100)
                    : 0;

                ExamSubjectScore::updateOrCreate(
                    [
                        'exam_session_id' => $session->id,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'correct_count' => $correctForSubject,
                        'total_questions' => $totalForSubject,
                        'score_over_100' => $scoreForSubject, // <- changed here
                    ]
                );
            }

            $totalJambScore = ExamSubjectScore::where('exam_session_id', $session->id)
                ->sum('score_over_100'); // <- changed here
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