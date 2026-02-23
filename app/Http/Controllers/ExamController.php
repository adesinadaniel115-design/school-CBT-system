<?php

namespace App\Http\Controllers;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\ExamSubjectScore;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ExamController extends Controller
{
    public function start(Request $request)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        $studentId = $request->user()->id;

        // Check for any active (ongoing) session
        $activeSession = ExamSession::where('student_id', $studentId)
            ->whereNull('completed_at')
            ->first();

        if ($activeSession) {
            // If same subject, resume the exam
            if ((int) $activeSession->subject_id === (int) $data['subject_id']) {
                return redirect()->route('exam.take', $activeSession);
            }

            // Prevent starting a new exam while another is ongoing
            return back()->withErrors([
                'subject_id' => 'Finish your current exam before starting another subject.',
            ]);
        }

        // School mode: configurable questions per subject
        $questionCount = Cache::get('school_questions_count', 40);
        $questions = Question::where('subject_id', $data['subject_id'])
            ->inRandomOrder()
            ->limit($questionCount)
            ->get();

        if ($questions->isEmpty()) {
            return back()->withErrors(['subject_id' => 'No questions available for this subject.']);
        }

        $questionIds = $questions->pluck('id')->all();

        $durationMinutes = Cache::get('school_duration_minutes', 60);

        $session = DB::transaction(function () use ($studentId, $data, $questionIds, $durationMinutes) {
            $session = ExamSession::create([
                'student_id' => $studentId,
                'subject_id' => $data['subject_id'],
                'exam_mode' => 'school',
                'total_questions' => count($questionIds),
                'duration_minutes' => $durationMinutes,
                'score' => 0,
                'question_ids' => $questionIds,
                'started_at' => now(),
            ]);

            $now = now();
            $answers = array_map(function ($questionId) use ($session, $now) {
                return [
                    'exam_session_id' => $session->id,
                    'question_id' => $questionId,
                    'selected_option' => null,
                    'is_correct' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $questionIds);

            ExamAnswer::insert($answers);

            return $session;
        });

        return redirect()->route('exam.take', $session);
    }

    public function startJamb(Request $request)
    {
        $data = $request->validate([
            'subject_ids' => ['required', 'array', 'size:3'],
            'subject_ids.*' => ['required', 'exists:subjects,id'],
        ]);

        $studentId = $request->user()->id;

        // Check for any active JAMB session
        $activeJambSession = ExamSession::where('student_id', $studentId)
            ->where('exam_mode', 'jamb')
            ->whereNull('completed_at')
            ->first();

        if ($activeJambSession) {
            return back()->withErrors([
                'subject_ids' => 'You have an active JAMB exam. Please complete it before starting another.',
            ]);
        }

        // Get English subject (compulsory)
        $englishSubject = Subject::where('name', 'LIKE', '%English%')
            ->orWhere('name', 'LIKE', '%ENGLISH%')
            ->first();

        if (!$englishSubject) {
            return back()->withErrors([
                'subject_ids' => 'English subject not found. Please contact administrator.',
            ]);
        }

        $selectedSubjectIds = $data['subject_ids'];

        // Ensure English is not in the selected 3 (will be added automatically)
        if (in_array($englishSubject->id, $selectedSubjectIds)) {
            return back()->withErrors([
                'subject_ids' => 'English is automatically included. Please select 3 other subjects.',
            ]);
        }

        // JAMB Mode: configurable questions from English + configurable from each of 3 selected subjects
        $englishQuestionCount = Cache::get('jamb_english_questions', 3);
        $subjectQuestionCount = Cache::get('jamb_questions_per_subject', 3);
        
        $englishQuestions = Question::where('subject_id', $englishSubject->id)
            ->inRandomOrder()
            ->limit($englishQuestionCount)
            ->get();

        if ($englishQuestions->count() < $englishQuestionCount) {
            return back()->withErrors([
                'subject_ids' => "Insufficient English questions (need {$englishQuestionCount}).",
            ]);
        }

        // Collect all questions
        $allQuestions = collect([$englishQuestions]);
        $subjectMap = [];

        // Track which subjects are included
        $subjectMap[$englishSubject->id] = [
            'name' => $englishSubject->name,
            'count' => $englishQuestionCount,
        ];

        foreach ($selectedSubjectIds as $subjectId) {
            $subject = Subject::find($subjectId);
            $questions = Question::where('subject_id', $subjectId)
                ->inRandomOrder()
                ->limit($subjectQuestionCount)
                ->get();

            if ($questions->count() < $subjectQuestionCount) {
                return back()->withErrors([
                    'subject_ids' => "Insufficient questions for {$subject->name} (need {$subjectQuestionCount}).",
                ]);
            }

            $allQuestions->push($questions);
            $subjectMap[$subjectId] = [
                'name' => $subject->name,
                'count' => $subjectQuestionCount,
            ];
        }

        // Flatten and shuffle all questions
        $allQuestions = $allQuestions->flatten();
        $shuffledQuestions = $allQuestions->shuffle();
        $questionIds = $shuffledQuestions->pluck('id')->all();
        $totalQuestions = count($questionIds);
        $jambDuration = Cache::get('jamb_duration_minutes', 120);

        // Create JAMB session
        $session = DB::transaction(function () use ($studentId, $englishSubject, $selectedSubjectIds, $questionIds, $totalQuestions, $jambDuration) {
            $session = ExamSession::create([
                'student_id' => $studentId,
                'subject_id' => $englishSubject->id, // Store English as primary for reference
                'exam_mode' => 'jamb',
                'total_questions' => $totalQuestions,
                'duration_minutes' => $jambDuration,
                'score' => 0,
                'question_ids' => $questionIds,
                'started_at' => now(),
            ]);

            // Create answer records for all questions
            $now = now();
            $answers = array_map(function ($questionId) use ($session, $now) {
                return [
                    'exam_session_id' => $session->id,
                    'question_id' => $questionId,
                    'selected_option' => null,
                    'is_correct' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $questionIds);

            ExamAnswer::insert($answers);

            return $session;
        });

        return redirect()->route('exam.take', $session);
    }

    public function take(ExamSession $session)
    {
        $this->authorizeSession($session);

        if ($session->completed_at) {
            return redirect()->route('exam.result', $session)
                ->with('warning', 'This exam has already been submitted. You cannot retake it.');
        }

        $questionIds = array_map('intval', $session->question_ids ?? []);
        if (empty($questionIds)) {
            abort(400, 'Session has no questions.');
        }

        // Load questions with subject for JAMB mode
        $questions = Question::with('subject')
            ->whereIn('id', $questionIds)
            ->orderByRaw('FIELD(id, '.implode(',', $questionIds).')')
            ->get();

        $answers = $session->answers()->get()->keyBy('question_id');

        return view('exam.take', [
            'session' => $session->load('subject'),
            'questions' => $questions,
            'answers' => $answers,
            'allowFlagging' => Cache::get('allow_question_flagging', true),
        ]);
    }

    public function submit(Request $request, ExamSession $session)
    {
        $this->authorizeSession($session);

        if ($session->completed_at) {
            return redirect()->route('exam.result', $session);
        }

        $request->validate([
            'answers' => ['array'],
        ]);

        $questionIds = array_map('intval', $session->question_ids ?? []);
        $questions = Question::with('subject')->whereIn('id', $questionIds)->get()->keyBy('id');
        $submittedAnswers = $request->input('answers', []);

        if ($session->exam_mode === 'jamb') {
            $this->submitJambExam($session, $questions, $submittedAnswers);
        } else {
            $this->submitSchoolExam($session, $questions, $submittedAnswers);
        }

        if (!Cache::get('show_results_immediately', true)) {
            return redirect()->route('student.dashboard')
                ->with('status', 'Exam submitted successfully. Results will be available later.');
        }

        return redirect()->route('exam.result', $session);
    }

    public function saveAnswer(Request $request, ExamSession $session)
    {
        $this->authorizeSession($session);

        if ($session->completed_at) {
            return response()->json(['message' => 'Exam already completed.'], 409);
        }

        $data = $request->validate([
            'question_id' => ['required', 'integer'],
            'selected_option' => ['nullable', 'in:A,B,C,D'],
        ]);

        $questionIds = array_map('intval', $session->question_ids ?? []);
        if (!in_array((int) $data['question_id'], $questionIds, true)) {
            return response()->json(['message' => 'Invalid question.'], 422);
        }

        $question = Question::find($data['question_id']);
        if (!$question) {
            return response()->json(['message' => 'Question not found.'], 404);
        }

        $isCorrect = $data['selected_option']
            ? $data['selected_option'] === $question->correct_option
            : false;

        ExamAnswer::where('exam_session_id', $session->id)
            ->where('question_id', $data['question_id'])
            ->update([
                'selected_option' => $data['selected_option'],
                'is_correct' => $isCorrect,
            ]);

        return response()->json(['saved' => true]);
    }

    public function terminate(ExamSession $session)
    {
        $this->authorizeSession($session);

        if ($session->completed_at) {
            return redirect()->route('exam.result', $session);
        }

        DB::transaction(function () use ($session) {
            ExamSubjectScore::where('exam_session_id', $session->id)->delete();

            $session->update([
                'score' => 0,
                'completed_at' => now(),
            ]);
        });

        return redirect()->route('student.dashboard')
            ->with('status', 'Exam terminated successfully.');
    }

    private function submitSchoolExam(ExamSession $session, $questions, $submittedAnswers): void
    {
        $score = 0;

        DB::transaction(function () use ($session, $questions, $submittedAnswers, &$score) {
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
        });
    }

    private function submitJambExam(ExamSession $session, $questions, $submittedAnswers): void
    {
        DB::transaction(function () use ($session, $questions, $submittedAnswers) {
            // Group questions by subject
            $subjectScores = [];

            foreach ($questions as $questionId => $question) {
                $selected = strtoupper((string) ($submittedAnswers[$questionId] ?? ''));
                $selected = in_array($selected, ['A', 'B', 'C', 'D'], true) ? $selected : null;
                $isCorrect = $selected && $selected === $question->correct_option;

                // Update answer
                ExamAnswer::where('exam_session_id', $session->id)
                    ->where('question_id', $questionId)
                    ->update([
                        'selected_option' => $selected,
                        'is_correct' => $isCorrect,
                    ]);

                // Track per-subject scores
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

            // Calculate and save per-subject scores
            $totalScore = 0;

            foreach ($subjectScores as $subjectId => $scoreData) {
                $correctCount = $scoreData['correct'];
                $totalQuestions = $scoreData['total'];
                $scoreOver100 = ($correctCount / $totalQuestions) * 100;

                ExamSubjectScore::create([
                    'exam_session_id' => $session->id,
                    'subject_id' => $subjectId,
                    'correct_count' => $correctCount,
                    'score_over_100' => $scoreOver100,
                ]);

                $totalScore += $scoreOver100;
            }

            // Update session with total score (max 400)
            $session->update([
                'score' => $totalScore,
                'completed_at' => now(),
            ]);
        });
    }

    public function result(ExamSession $session)
    {
        $this->authorizeSession($session);

        if (!$session->completed_at) {
            return redirect()->route('exam.take', $session);
        }

        if (!Cache::get('show_results_immediately', true) && !auth()->user()->is_admin) {
            return redirect()->route('student.dashboard')
                ->with('status', 'Your exam has been submitted. Results will be available later.');
        }

        // Load relationships based on exam mode
        if ($session->exam_mode === 'jamb') {
            $session->load(['examSubjectScores.subject']);
        } else {
            $session->load('subject');
        }

        return view('exam.result', [
            'session' => $session,
            'allowReview' => Cache::get('allow_exam_review', false),
        ]);
    }

    public function review(ExamSession $session)
    {
        $this->authorizeSession($session);

        // Only allow review if exam is completed and setting is enabled
        if (!$session->completed_at) {
            return redirect()->route('exam.take', $session);
        }

        if (!Cache::get('allow_exam_review', false)) {
            return redirect()->route('exam.result', $session)
                ->with('error', 'Exam review is not allowed.');
        }

        // Load all questions with their answers
        $questionIds = array_map('intval', $session->question_ids ?? []);
        $questions = Question::with('subject')
            ->whereIn('id', $questionIds)
            ->orderByRaw('FIELD(id, '.implode(',', $questionIds).')')
            ->get();

        $answers = $session->answers()->get()->keyBy('question_id');

        return view('exam.review', [
            'session' => $session->load('subject'),
            'questions' => $questions,
            'answers' => $answers,
        ]);
    }

    private function authorizeSession(ExamSession $session): void
    {
        if ($session->student_id !== auth()->id()) {
            abort(403);
        }
    }
}
