<?php

namespace App\Http\Controllers;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\ExamSubjectScore;
use App\Models\ExamToken;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $shuffleQuestions = Cache::get('shuffle_questions', true);
        
        $query = Question::where('subject_id', $data['subject_id']);
        
        if ($shuffleQuestions) {
            $query->inRandomOrder();
        }
        
        $questions = $query->limit($questionCount)->get();

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

        // Validate that enough questions exist
        $englishQuestionCount = Cache::get('jamb_english_questions', 3);
        $subjectQuestionCount = Cache::get('jamb_questions_per_subject', 3);
        
        $englishQuestionsAvailable = Question::where('subject_id', $englishSubject->id)->count();

        if ($englishQuestionsAvailable < $englishQuestionCount) {
            return back()->withErrors([
                'subject_ids' => "Insufficient English questions (need {$englishQuestionCount}).",
            ]);
        }

        foreach ($selectedSubjectIds as $subjectId) {
            $subject = Subject::find($subjectId);
            $availableCount = Question::where('subject_id', $subjectId)->count();

            if ($availableCount < $subjectQuestionCount) {
                return back()->withErrors([
                    'subject_ids' => "Insufficient questions for {$subject->name} (need {$subjectQuestionCount}).",
                ]);
            }
        }

        // Store selection in session and redirect to confirmation page
        $selectedSubjects = Subject::whereIn('id', $selectedSubjectIds)->get();
        
        return view('student.exam.confirm-jamb', [
            'englishSubject' => $englishSubject,
            'selectedSubjects' => $selectedSubjects,
            'subjectIds' => $selectedSubjectIds,
            'englishQuestionCount' => $englishQuestionCount,
            'subjectQuestionCount' => $subjectQuestionCount,
            'duration' => Cache::get('jamb_duration_minutes', 120),
        ]);
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
        
        // Shuffle options if enabled
        $shuffleOptions = Cache::get('shuffle_options', true);
        if ($shuffleOptions) {
            $questions = $this->shuffleQuestionOptions($questions, $session->id);
        }

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
        
        // Shuffle options if enabled (same as during exam)
        $shuffleOptions = Cache::get('shuffle_options', true);
        if ($shuffleOptions) {
            $questions = $this->shuffleQuestionOptions($questions, $session->id);
        }

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
    
    /**
     * Shuffle question options (A/B/C/D) for each question
     * Uses session ID as seed to ensure consistent shuffling per session
     */
    private function shuffleQuestionOptions($questions, $sessionId)
    {
        foreach ($questions as $question) {
            // Use session ID + question ID as seed for consistent shuffling
            mt_srand($sessionId + $question->id);
            
            // Create array of options with their original labels
            $options = [
                'A' => $question->option_a,
                'B' => $question->option_b,
                'C' => $question->option_c,
                'D' => $question->option_d,
            ];
            
            // Shuffle while preserving keys
            $keys = array_keys($options);
            shuffle($keys);
            $shuffledOptions = [];
            $mapping = [];
            
            // Map old position to new position
            $newLabels = ['A', 'B', 'C', 'D'];
            foreach ($keys as $index => $originalKey) {
                $newLabel = $newLabels[$index];
                $shuffledOptions[$newLabel] = $options[$originalKey];
                $mapping[$originalKey] = $newLabel;
            }
            
            // Update question options
            $question->option_a = $shuffledOptions['A'];
            $question->option_b = $shuffledOptions['B'];
            $question->option_c = $shuffledOptions['C'];
            $question->option_d = $shuffledOptions['D'];
            
            // Update correct answer to new position
            $question->original_correct_option = $question->correct_option;
            $question->correct_option = $mapping[$question->correct_option];
            
            // Store mapping for reference (optional, for debugging)
            $question->option_mapping = $mapping;
            
            // Reset random seed
            mt_srand();
        }
        
        return $questions;
    }

    public function confirmJamb(Request $request)
    {
        $data = $request->validate([
            'subject_ids' => ['required', 'array', 'size:3'],
            'subject_ids.*' => ['required', 'exists:subjects,id'],
            'token_code' => ['required', 'string'],
        ]);

        $studentId = $request->user()->id;
        $tokenCode = strtoupper(trim($data['token_code']));

        // Validate token
        $token = ExamToken::where('code', $tokenCode)->first();

        if (!$token) {
            return back()->withErrors(['token_code' => 'Invalid token code.'])->withInput();
        }

        if (!$token->isValid()) {
            $reason = !$token->is_active ? 'deactivated' :
                     ($token->expires_at && $token->expires_at->isPast() ? 'expired' : 'fully used');
            return back()->withErrors(['token_code' => "Token is {$reason}."])->withInput();
        }

        // Get English subject
        $englishSubject = Subject::where('name', 'LIKE', '%English%')
            ->orWhere('name', 'LIKE', '%ENGLISH%')
            ->first();

        $selectedSubjectIds = $data['subject_ids'];

        // Collect questions
        $englishQuestionCount = Cache::get('jamb_english_questions', 3);
        $subjectQuestionCount = Cache::get('jamb_questions_per_subject', 3);
        $shuffleQuestions = Cache::get('shuffle_questions', true);
        
        $englishQuery = Question::where('subject_id', $englishSubject->id);
        
        if ($shuffleQuestions) {
            $englishQuery->inRandomOrder();
        }
        
        $englishQuestions = $englishQuery->limit($englishQuestionCount)->get();

        $allQuestions = collect([$englishQuestions]);
        $subjectMap = [];

        $subjectMap[$englishSubject->id] = [
            'name' => $englishSubject->name,
            'count' => $englishQuestionCount,
        ];

        foreach ($selectedSubjectIds as $subjectId) {
            $subject = Subject::find($subjectId);
            
            $subjectQuery = Question::where('subject_id', $subjectId);
            
            if ($shuffleQuestions) {
                $subjectQuery->inRandomOrder();
            }
            
            $questions = $subjectQuery->limit($subjectQuestionCount)->get();

            $allQuestions->push($questions);
            $subjectMap[$subjectId] = [
                'name' => $subject->name,
                'count' => $subjectQuestionCount,
            ];
        }

        // Flatten and shuffle all questions
        $allQuestions = $allQuestions->flatten();
        
        if ($shuffleQuestions) {
            $allQuestions = $allQuestions->shuffle();
        }
        
        $questionIds = $allQuestions->pluck('id')->all();
        $totalQuestions = count($questionIds);
        $jambDuration = Cache::get('jamb_duration_minutes', 120);

        // Create JAMB session
        $session = DB::transaction(function () use ($studentId, $englishSubject, $selectedSubjectIds, $questionIds, $totalQuestions, $jambDuration, $token, $request) {
            $session = ExamSession::create([
                'student_id' => $studentId,
                'subject_id' => $englishSubject->id,
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

            // Use the token
            $token->use($request->user(), $session->id);

            return $session;
        });

        return redirect()->route('exam.take', $session);
    }

    public function validateToken(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string']
        ]);

        $token = ExamToken::where('code', strtoupper(trim($request->code)))->first();

        if (!$token) {
            return response()->json([
                'valid' => false,
                'message' => 'Token not found'
            ], 404);
        }

        if (!$token->isValid()) {
            $reason = !$token->is_active ? 'deactivated' :
                     ($token->expires_at && $token->expires_at->isPast() ? 'expired' : 'fully used');
            
            return response()->json([
                'valid' => false,
                'message' => "Token is {$reason}"
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Token is valid',
            'remaining_uses' => $token->remainingUses()
        ]);
    }}