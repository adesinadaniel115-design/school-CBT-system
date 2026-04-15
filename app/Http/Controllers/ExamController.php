<?php

namespace App\Http\Controllers;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\ExamSubjectScore;
use App\Models\ExamToken;
use App\Models\Question;
use App\Models\Subject;
use App\Models\StudentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function startSchool(Request $request)
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

        // Validate subject exists
        $subject = Subject::find($data['subject_id']);
        if (!$subject) {
            return back()->withErrors(['subject_id' => 'Subject not found.']);
        }

        // School mode: configurable questions per subject
        $questionCount = Cache::get('school_questions_count', 40);
        
        // Avoid reusing questions the student already attempted (if possible)
        $usedQuestionIds = ExamSession::where('student_id', $studentId)
            ->pluck('question_ids')
            ->filter()
            ->map(function ($ids) {
                return is_array($ids)
                    ? $ids
                    : (json_decode($ids, true) ?: []);
            })->flatten()->unique()->values()->all();

        $baseQuery = Question::where('subject_id', $data['subject_id']);
        $newQuery = $baseQuery->when(!empty($usedQuestionIds), function ($q) use ($usedQuestionIds) {
            return $q->whereNotIn('id', $usedQuestionIds);
        });

        $newAvailable = $newQuery->count();

        if ($newAvailable < $questionCount) {
            // Not enough fresh questions — we'll allow mixing previously-used questions
            $questionsAvailable = $baseQuery->count();
            if ($questionsAvailable < $questionCount) {
                return back()->withErrors([
                    'subject_id' => "Insufficient questions for this subject (need {$questionCount}).",
                ]);
            }
        }

        // Redirect to confirmation page with subject info
        $durationMinutes = Cache::get('school_duration_minutes', 60);

        return view('student.exam.confirm-school', [
            'subject' => $subject,
            'subjectId' => $data['subject_id'],
            'questionCount' => $questionCount,
            'duration' => $durationMinutes,
        ]);
    }

    public function confirmSchool(Request $request)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'token_code' => ['nullable', 'string'],
        ]);

        $studentId = $request->user()->id;
        $tokenCode = strtoupper(trim($data['token_code'] ?? ''));
        $token = null;

        if ($tokenCode !== '') {
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
        } elseif (config('app.offline_mode') && config('app.offline_monthly_access') && $request->user()->hasActivePackage()) {
            // Offline monthly access path: no token required when active package exists
            $activeStudentPlan = $request->user()->studentPlans()
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->where('attempts_remaining', '>', 0)
                ->orderBy('expires_at', 'desc')
                ->first();

            if (!$activeStudentPlan) {
                return back()->withErrors(['token_code' => 'No active package found.'])->withInput();
            }
        } else {
            return back()->withErrors(['token_code' => 'Token code is required.'])->withInput();
        }

        // Check for any active (ongoing) session again to be safe
        $activeSession = ExamSession::where('student_id', $studentId)
            ->whereNull('completed_at')
            ->first();

        if ($activeSession) {
            return back()->withErrors([
                'subject_id' => 'You have an active exam. Please complete it before starting another.',
            ]);
        }

        // Collect questions
        $questionCount = Cache::get('school_questions_count', 40);
        $shuffleQuestions = Cache::get('shuffle_questions', true);
        // allow premium plan to change count
        $activePlan = $request->user()->activePlan();
        if ($activePlan && !is_null($activePlan->school_questions)) {
            $questionCount = $activePlan->school_questions;
        }
        
        // build list of previously attempted question IDs so we can avoid repeats
        $usedQuestionIds = ExamSession::where('student_id', $studentId)
            ->pluck('question_ids')
            ->filter()
            ->map(function ($ids) {
                return is_array($ids)
                    ? $ids
                    : (json_decode($ids, true) ?: []);
            })->flatten()->unique()->values()->all();
        
        // Prefer fresh questions the student hasn't seen before, but fall back to older ones
        $allQuestions = Question::where('subject_id', $data['subject_id'])->get();
        // separate fresh vs used in PHP to avoid SQLite parameter limits
        if (!empty($usedQuestionIds)) {
            $fresh = $allQuestions->whereNotIn('id', $usedQuestionIds);
        } else {
            $fresh = $allQuestions;
        }

        if ($shuffleQuestions) {
            $fresh = $fresh->shuffle();
        }

        $selected = $fresh->take($questionCount);

        if ($selected->count() < $questionCount) {
            $needed = $questionCount - $selected->count();
            $remaining = $allQuestions->whereNotIn('id', $selected->pluck('id')->all());
            if ($shuffleQuestions) {
                $remaining = $remaining->shuffle();
            }
            $selected = $selected->merge($remaining->take($needed));
        }

        $questions = $selected->values();

        // debugging: during tests, output the arrays so we can see what was chosen

        if ($questions->isEmpty()) {
            return back()->withErrors(['subject_id' => 'No questions available for this subject.']);
        }

        $questionIds = $questions->pluck('id')->all();
        $durationMinutes = Cache::get('school_duration_minutes', 60);

        // Create exam session and consume token within a single transaction.
        // We need to use the token first so that the StudentPlan record exists
        // before we attempt to decrement it.
        $session = DB::transaction(function () use ($studentId, $data, $questionIds, $durationMinutes, $token, $request) {
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

            if ($token) {
                // consume the token, binding it and creating StudentPlan if needed
                $ip = request()->ip();
                $ua = request()->header('User-Agent');
                $ok = $token->use(request()->user(), $session->id, $ip, $ua);
                if (!$ok) {
                    throw new \Exception('Token cannot be used by this account; it may have been used by another user.');
                }

                // Log token usage for debugging
                \Log::info('Token used for exam session', [
                    'session_id' => $session->id,
                    'student_id' => $session->student_id,
                    'token_id' => $token->id,
                    'token_has_plan' => $token->plan_id ? true : false,
                    'exam_mode' => $session->exam_mode
                ]);

                // now that the token has been used we can locate the plan record
                if ($token->plan_id) {
                    $record = $request->user()->studentPlans()
                        ->where('plan_id', $token->plan_id)
                        ->latest()
                        ->first();
                    if ($record) {
                        $record->decrement('attempts_remaining');
                        $session->student_plan_id = $record->id;
                        $session->save();
                        
                        \Log::info('StudentPlan updated for exam', [
                            'session_id' => $session->id,
                            'student_plan_id' => $record->id,
                            'attempts_remaining' => $record->attempts_remaining
                        ]);
                    } else {
                        \Log::warning('StudentPlan not found after token use', [
                            'session_id' => $session->id,
                            'student_id' => $session->student_id,
                            'token_plan_id' => $token->plan_id
                        ]);
                    }
                } else {
                    \Log::info('Token has no plan (legacy token)', [
                        'session_id' => $session->id,
                        'token_id' => $token->id,
                        'student_id' => $session->student_id
                    ]);
                }
            } else {
                // Offline package path, consume one attempt from active plan record (if available)
                if (isset($activeStudentPlan) && $activeStudentPlan) {
                    $activeStudentPlan->decrement('attempts_remaining');
                    $session->student_plan_id = $activeStudentPlan->id;
                    $session->save();

                    \Log::info('Offline package StudentPlan consumption', [
                        'session_id' => $session->id,
                        'student_plan_id' => $activeStudentPlan->id,
                        'attempts_remaining' => $activeStudentPlan->attempts_remaining
                    ]);
                } else {
                    throw new \Exception('No active offline package found.');
                }
            }

            return $session;
        });

        return redirect()->route('exam.take', $session);
    }

    public function startJamb(Request $request)
    {
        $data = $request->validate([
            'subject_ids' => ['required', 'array', 'size:3', 'distinct'],
            'subject_ids.*' => ['required', 'exists:subjects,id'],
        ]);

        // Although Laravel's "distinct" rule should catch duplicates, we've
        // seen odd behavior in the test environment where the rule seems to be
        // bypassed.  Guard with an explicit PHP check so we reliably return an
        // error message and keep the tests stable.
        $ids = $data['subject_ids'] ?? [];
        if (count($ids) !== count(array_unique($ids))) {
            return back()->withErrors([
                'subject_ids' => 'Please select three different subjects.',
            ]);
        }

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
        $englishQuestionCount = Cache::get('jamb_english_questions', 60);
        $subjectQuestionCount = Cache::get('jamb_questions_per_subject', 40);
        // apply plan override if student has one
        $activePlan = $request->user()->activePlan();
        if ($activePlan) {
            if (!is_null($activePlan->jamb_questions_per_subject)) {
                $subjectQuestionCount = $activePlan->jamb_questions_per_subject;
            }
            if (!is_null($activePlan->jamb_english_questions)) {
                $englishQuestionCount = $activePlan->jamb_english_questions;
            }
        }
        
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
            'englishQuestionsAvailable' => $englishQuestionsAvailable,
            'duration' => Cache::get('jamb_duration_minutes', 120),
                'englishQuestionsAvailable' => $englishQuestionsAvailable,
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
            ->get()
            ->sortBy(function($q) use ($questionIds) {
                return array_search($q->id, $questionIds);
            })->values();

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
        // allow the script more time to grade large exams – default 60s may be hit
        @set_time_limit(120);

        $this->authorizeSession($session);

        if ($session->completed_at) {
            return redirect()->route('exam.result', $session);
        }

        $request->validate([
            'answers' => ['array'],
            'answers.*' => ['nullable', 'in:A,B,C,D'],
        ]);

        $submittedAnswers = $request->input('answers', []);
        $questionIds = array_map('intval', $session->question_ids ?? []);

        // ensure student isn't submitting answers for questions outside of session
        $invalidKeys = array_diff(array_map('intval', array_keys($submittedAnswers)), $questionIds);
        if (!empty($invalidKeys)) {
            \Log::warning('Invalid answers in submission', [
                'session_id' => $session->id,
                'student_id' => $session->student_id,
                'invalid_keys' => $invalidKeys,
                'submitted_keys' => array_keys($submittedAnswers)
            ]);
            return back()->withErrors(['answers' => 'Invalid question answered.'])->withInput();
        }

        $questions = Question::with('subject')->whereIn('id', $questionIds)->get();
        
        // Apply shuffle if enabled to get correct shuffled correct_option
        $shuffleOptions = Cache::get('shuffle_options', true);
        if ($shuffleOptions) {
            $questions = $this->shuffleQuestionOptions($questions, $session->id);
        }
        
        $questions = $questions->keyBy('id');
        $submittedAnswers = $request->input('answers', []);

        // Log submission attempt
        \Log::info('exam submission started', [
            'session_id' => $session->id,
            'student_id' => $session->student_id,
            'student_plan_id' => $session->student_plan_id,
            'exam_mode' => $session->exam_mode,
            'total_questions' => $session->total_questions,
            'submitted_answers_count' => count(array_filter($submittedAnswers, fn($v) => $v !== null))
        ]);

        try {
            if ($session->exam_mode === 'jamb') {
                $this->submitJambExam($session, $questions, $submittedAnswers);
            } else {
                $this->submitSchoolExam($session, $questions, $submittedAnswers);
            }
            
            // Log successful submission
            \Log::info('exam submission completed successfully', [
                'session_id' => $session->id,
                'student_id' => $session->student_id,
                'score' => $session->fresh()->score
            ]);
        } catch (\Throwable $e) {
            // log it so administrators can investigate rather than dropping to 500
            \Log::error('error submitting exam session '.$session->id, [
                'exception' => $e,
                'student_id' => $session->student_id,
                'student_plan_id' => $session->student_plan_id,
                'exam_mode' => $session->exam_mode,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('student.dashboard')
                         ->withErrors('An error occurred while submitting your exam. Please try again or contact support.');
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
            \Log::warning('Attempted to save answer for missing question', [
                'session_id' => $session->id,
                'question_id' => $data['question_id'],
                'student_id' => $session->student_id,
            ]);
            return response()->json(['message' => 'Question not found.'], 404);
        }

        // Apply shuffle to get the correct shuffled correct_option
        $shuffleOptions = Cache::get('shuffle_options', true);
        if ($shuffleOptions) {
            $question = $this->shuffleQuestionOptions(collect([$question]), $session->id)->first();
        }

        $selectedOption = $data['selected_option'] ?? null;
        $isCorrect = $selectedOption
            ? $selectedOption === $question->correct_option
            : false;

        $answer = ExamAnswer::updateOrCreate(
            [
                'exam_session_id' => $session->id,
                'question_id' => $data['question_id'],
            ],
            [
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
            ]
        );

        \Log::info('exam answer saved', [
            'session_id' => $session->id,
            'student_id' => $session->student_id,
            'question_id' => $question->id,
            'selected_option' => $data['selected_option'],
            'is_correct' => $isCorrect,
            'answer_id' => $answer->id,
        ]);

        return response()->json([
            'saved' => true,
            'question_id' => $data['question_id'],
            'selected_option' => $data['selected_option'],
        ]);
    }

    public function terminate(ExamSession $session)
    {
        // Allow admin or the student who owns the session to terminate
        if (!auth()->user()->is_admin) {
            $this->authorizeSession($session);
        }

        if ($session->completed_at) {
            $redirectTo = auth()->user()->is_admin ? route('admin.dashboard') : route('student.dashboard');
            return redirect($redirectTo);
        }

        DB::transaction(function () use ($session) {
            ExamSubjectScore::where('exam_session_id', $session->id)->delete();

            $session->update([
                'score' => 0,
                'completed_at' => now(),
            ]);
        });

        $redirectTo = auth()->user()->is_admin ? route('admin.dashboard') : route('student.dashboard');
        $message = auth()->user()->is_admin ? 'Exam session terminated.' : 'Exam terminated successfully.';
        return redirect($redirectTo)->with('status', $message);
    }

    /**
     * Force-submit an incomplete exam by grading all current answers
     * Used when a student is done but hasn't clicked submit
     * Available to students and admins
     */
    public function forceSubmit(ExamSession $session)
    {
        // Allow admin or the student who owns the session to force-submit
        if (!auth()->user()->is_admin) {
            $this->authorizeSession($session);
        }

        if ($session->completed_at) {
            return redirect()->route('exam.result', $session)
                ->with('info', 'This exam has already been submitted.');
        }

        @set_time_limit(120);

        $questionIds = array_map('intval', $session->question_ids ?? []);
        if (empty($questionIds)) {
            abort(400, 'Session has no questions.');
        }

        $questions = Question::with('subject')->whereIn('id', $questionIds)->get();
        
        // Apply shuffle if enabled to get correct shuffled correct_option
        $shuffleOptions = Cache::get('shuffle_options', true);
        if ($shuffleOptions) {
            $questions = $this->shuffleQuestionOptions($questions, $session->id);
        }
        
        $questions = $questions->keyBy('id');
        
        // Get all current answers from the database (answers student has saved so far)
        $answers = $session->answers()->get()->keyBy('question_id');
        $submittedAnswers = [];
        
        foreach ($answers as $questionId => $answer) {
            $submittedAnswers[$questionId] = $answer->selected_option;
        }

        try {
            \Log::info('force submit exam', [
                'session_id' => $session->id,
                'student_id' => $session->student_id,
                'exam_mode' => $session->exam_mode,
                'submitted_by' => auth()->user()->is_admin ? 'admin' : 'student',
                'answers_count' => count(array_filter($submittedAnswers, fn($v) => $v !== null))
            ]);

            if ($session->exam_mode === 'jamb') {
                $this->submitJambExam($session, $questions, $submittedAnswers);
            } else {
                $this->submitSchoolExam($session, $questions, $submittedAnswers);
            }

            \Log::info('force submit completed', [
                'session_id' => $session->id,
                'student_id' => $session->student_id,
                'score' => $session->fresh()->score
            ]);
        } catch (\Throwable $e) {
            \Log::error('error force submitting exam', [
                'session_id' => $session->id,
                'student_id' => $session->student_id,
                'exception' => $e,
                'message' => $e->getMessage()
            ]);
            
            $redirectTo = auth()->user()->is_admin ? route('admin.dashboard') : route('student.dashboard');
            return redirect($redirectTo)
                ->withErrors('An error occurred while submitting your exam. Please contact support.');
        }

        return redirect()->route('exam.result', $session)
            ->with('status', 'Exam submitted successfully!');
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

    public function review(ExamSession $session, Request $request)
    {
        $this->authorizeSession($session);

        // Only allow review if exam is completed and setting is enabled
        if (!$session->completed_at) {
            return redirect()->route('exam.take', $session);
        }

        // Allow review if global setting enabled OR user has an active premium plan
        $globalAllow = Cache::get('allow_exam_review', false);
        $hasPremium = auth()->user()->activePlan() !== null;
        if (!$globalAllow && !$hasPremium) {
            return redirect()->route('exam.result', $session)
                ->with('error', 'Exam review is not allowed.');
        }

        // Get all question IDs in order
        $allQuestionIds = array_map('intval', $session->question_ids ?? []);
        $totalQuestions = count($allQuestionIds);
        
        // Paginate questions (default 10 per page to provide more pages)
        $perPage = (int) $request->get('per_page', 10);
        // clamp perPage to reasonable bounds
        if ($perPage < 5) $perPage = 5;
        if ($perPage > 100) $perPage = 100;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        // Get question IDs for current page
        $paginatedQuestionIds = array_slice($allQuestionIds, $offset, $perPage);
        
        // Load questions for current page only
        $questions = Question::with('subject')
            ->whereIn('id', $paginatedQuestionIds)
            ->get()
            ->sortBy(function($q) use ($paginatedQuestionIds) {
                return array_search($q->id, $paginatedQuestionIds);
            })->values();

        $answers = $session->answers()->get()->keyBy('question_id');
        
        // Shuffle options if enabled (same as during exam)
        $shuffleOptions = Cache::get('shuffle_options', true);
        if ($shuffleOptions) {
            $questions = $this->shuffleQuestionOptions($questions, $session->id);
        }

        // Create a proper LengthAwarePaginator instance
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $questions->values(),
            $totalQuestions,
            $perPage,
            $currentPage,
            [
                'path' => route('exam.review', $session),
                'query' => $request->query(),
            ]
        );
        // Show a larger range of page links so users can navigate across many pages
        $paginator->onEachSide(10);

        return view('exam.review', [
            'session' => $session->load('subject'),
            'questions' => $paginator,
            'answers' => $answers,
            'totalQuestions' => $totalQuestions,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
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
            'token_code' => ['nullable', 'string'],
        ]);

        $studentId = $request->user()->id;
        $tokenCode = strtoupper(trim($data['token_code'] ?? ''));
        $token = null;

        if ($tokenCode !== '') {
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
        } elseif (config('app.offline_mode') && config('app.offline_monthly_access') && $request->user()->hasActivePackage()) {
            $activeStudentPlan = $request->user()->studentPlans()
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->where('attempts_remaining', '>', 0)
                ->orderBy('expires_at', 'desc')
                ->first();

            if (!$activeStudentPlan) {
                return back()->withErrors(['token_code' => 'No active package found.'])->withInput();
            }
        } else {
            return back()->withErrors(['token_code' => 'Token code is required.'])->withInput();
        }

        // Get English subject
        $englishSubject = Subject::where('name', 'LIKE', '%English%')
            ->orWhere('name', 'LIKE', '%ENGLISH%')
            ->first();

        $selectedSubjectIds = $data['subject_ids'];

        // Collect questions
        $englishQuestionCount = Cache::get('jamb_english_questions', 60);
        $subjectQuestionCount = Cache::get('jamb_questions_per_subject', 40);
        $shuffleQuestions = Cache::get('shuffle_questions', true);
        // plan override
        $activePlan = $request->user()->activePlan();
        if ($activePlan) {
            if (!is_null($activePlan->jamb_questions_per_subject)) {
                $subjectQuestionCount = $activePlan->jamb_questions_per_subject;
            }
            if (!is_null($activePlan->jamb_english_questions)) {
                $englishQuestionCount = $activePlan->jamb_english_questions;
            }
        }
        
        // Build list of previously attempted question IDs for this student
        $usedQuestionIds = ExamSession::where('student_id', $studentId)
            ->pluck('question_ids')
            ->filter()
            ->map(function ($ids) {
                return is_array($ids)
                    ? $ids
                    : (json_decode($ids, true) ?: []);
            })->flatten()->unique()->values()->all();

        // Helper: select fresh questions first, then fallback to older ones if needed
        $selectQuestions = function ($subjectId, $count) use ($shuffleQuestions, $usedQuestionIds) {
            $selected = collect();

            // fresh questions query
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

                // fallback query starts fresh again (do not reuse $freshQ)
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

        $englishQuestions = $selectQuestions($englishSubject->id, $englishQuestionCount);

        $allQuestions = collect([$englishQuestions]);
        $subjectMap = [];

        $subjectMap[$englishSubject->id] = [
            'name' => $englishSubject->name,
            'count' => $englishQuestionCount,
        ];

        foreach ($selectedSubjectIds as $subjectId) {
            $subject = Subject::find($subjectId);
            
            $questions = $selectQuestions($subjectId, $subjectQuestionCount);
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

        // Create JAMB session.  Attach plan record if present and decrement
        // attempts within the same transaction so we can record the association.
        $session = DB::transaction(function () use ($studentId, $englishSubject, $selectedSubjectIds, $questionIds, $totalQuestions, $jambDuration, $token, $request, $activePlan) {
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

            if ($token) {
                // Use the token first (capture IP/UA); this will bind the token and
                // create a StudentPlan record if the token grants a plan.
                $ip = $request->ip();
                $ua = $request->header('User-Agent');
                $ok = $token->use($request->user(), $session->id, $ip, $ua);
                if (!$ok) {
                    throw new \Exception('Token sharing detected');
                }

                // If the student has an active plan record (just created above),
                // consume an attempt and remember the record on the session so
                // features persist for this exam.
                if ($token->plan_id) {
                    $record = $request->user()->studentPlans()->where('plan_id', $token->plan_id)->latest()->first();
                    if ($record) {
                        $record->decrement('attempts_remaining');
                        $session->student_plan_id = $record->id;
                        $session->save();
                        
                        \Log::info('JAMB StudentPlan updated for exam', [
                            'session_id' => $session->id,
                            'student_plan_id' => $record->id,
                            'attempts_remaining' => $record->attempts_remaining
                        ]);
                    } else {
                        \Log::warning('JAMB StudentPlan not found after token use', [
                            'session_id' => $session->id,
                            'student_id' => $session->student_id,
                            'token_plan_id' => $token->plan_id
                        ]);
                    }
                } else {
                    \Log::info('JAMB Token has no plan (legacy token)', [
                        'session_id' => $session->id,
                        'token_id' => $token->id,
                        'student_id' => $session->student_id
                    ]);
                }
            } else {
                if (isset($activeStudentPlan) && $activeStudentPlan) {
                    $activeStudentPlan->decrement('attempts_remaining');
                    $session->student_plan_id = $activeStudentPlan->id;
                    $session->save();

                    \Log::info('JAMB offline package StudentPlan consumption', [
                        'session_id' => $session->id,
                        'student_plan_id' => $activeStudentPlan->id,
                        'attempts_remaining' => $activeStudentPlan->attempts_remaining
                    ]);
                } else {
                    throw new \Exception('No active offline package found.');
                }
            }

            return $session;
        });

        return redirect()->route('exam.take', $session);
    }

    public function validateToken(Request $request)
    {
        $request->validate([
            'code' => ['nullable', 'string']
        ]);

        $user = $request->user();
        $code = strtoupper(trim($request->code ?? ''));

        if ($code === '') {
            if (config('app.offline_mode') && config('app.offline_monthly_access') && $user && $user->hasActivePackage()) {
                $plan = $user->activePlan();
                return response()->json([
                    'valid' => true,
                    'message' => 'Offline monthly package is active. No token required.',
                    'token_plan' => true,
                    'plan' => $plan ? [
                        'name' => $plan->name,
                        'price' => $plan->price,
                        'attempts_allowed' => $plan->attempts_allowed,
                        'duration_days' => $plan->duration_days,
                        'has_explanations' => $plan->has_explanations,
                        'has_leaderboard' => $plan->has_leaderboard,
                        'has_streak' => $plan->has_streak,
                    ] : null,
                ]);
            }

            return response()->json([
                'valid' => false,
                'message' => 'Token code is required.'
            ], 400);
        }

        // Use strict case-sensitive search for token code
        $token = ExamToken::where('code', '=', $code)
            ->first();

        if (!$token) {
            \Log::warning('Token not found during validation', [
                'attempted_code' => $code,
                'user_id' => $user->id ?? null,
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'valid' => false,
                'message' => 'Token not found'
            ], 404);
        }

        // prevent validation if token has been bound to someone else
        $user = $request->user();
        if ($token->bound_user_id && $token->bound_user_id !== $user->id) {
            \Log::warning('Token already bound to different user', [
                'token_id' => $token->id,
                'token_code' => $token->code,
                'bound_user_id' => $token->bound_user_id,
                'current_user_id' => $user->id
            ]);
            
            return response()->json([
                'valid' => false,
                'message' => 'Token has already been redeemed by another user.'
            ], 403);
        }

        if (!$token->isValid()) {
            $reason = !$token->is_active ? 'deactivated' :
                     ($token->expires_at && $token->expires_at->isPast() ? 'expired' : 'fully used');
            
            \Log::warning('Token validation failed', [
                'token_id' => $token->id,
                'token_code' => $token->code,
                'reason' => $reason,
                'is_active' => $token->is_active,
                'expires_at' => $token->expires_at,
                'used_count' => $token->used_count,
                'max_uses' => $token->max_uses,
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'valid' => false,
                'message' => "Token is {$reason}"
            ], 400);
        }

        $resp = [
            'valid' => true,
            'message' => 'Token is valid',
            // always return numeric remaining_uses so admin can track token usage
            'remaining_uses' => $token->remainingUses(),
            'sharing_detected' => (bool) $token->sharing_detected,
            'bound_user_id' => $token->bound_user_id,
            'plan_token' => (bool) $token->plan_id,
        ];

        if (\Schema::hasTable('plans') && $token->plan) {
            $resp['plan'] = [
                'name' => $token->plan->name,
                'price' => $token->plan->price,
                'attempts_allowed' => $token->plan->attempts_allowed,
                'duration_days' => $token->plan->duration_days,
                'has_explanations' => $token->plan->has_explanations,
                'has_leaderboard' => $token->plan->has_leaderboard,
                'has_streak' => $token->plan->has_streak,
            ];

            // Get the student's current plan attempts remaining (if they already have this plan)
            if (\Schema::hasTable('student_plans') && $user) {
                $studentPlan = StudentPlan::where('student_id', $user->id)
                    ->where('plan_id', $token->plan_id)
                    ->first();
                
                if ($studentPlan) {
                    $resp['plan']['attempts_remaining'] = $studentPlan->attempts_remaining;
                } else {
                    // first time redeeming this plan - show full attempts
                    $resp['plan']['attempts_remaining'] = $token->plan->attempts_allowed;
                }
            }
        }

        return response()->json($resp);
    }}