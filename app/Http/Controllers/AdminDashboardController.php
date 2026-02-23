<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\Question;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $resetAt = Cache::get('admin_stats_reset_at');

        $stats = [
            'total_students' => User::where('is_admin', false)->count(),
            'total_subjects' => Subject::count(),
            'total_questions' => Question::count(),
            'total_exams' => ExamSession::whereNotNull('completed_at')
                ->when($resetAt, fn ($q) => $q->where('completed_at', '>=', $resetAt))
                ->count(),
            'active_exams' => ExamSession::whereNull('completed_at')
                ->when($resetAt, fn ($q) => $q->where('started_at', '>=', $resetAt))
                ->count(),
            'school_exams' => ExamSession::where('exam_mode', 'school')
                ->whereNotNull('completed_at')
                ->when($resetAt, fn ($q) => $q->where('completed_at', '>=', $resetAt))
                ->count(),
            'jamb_exams' => ExamSession::where('exam_mode', 'jamb')
                ->whereNotNull('completed_at')
                ->when($resetAt, fn ($q) => $q->where('completed_at', '>=', $resetAt))
                ->count(),
        ];

        // Recent activity
        $recentExams = ExamSession::with(['student', 'subject'])
            ->whereNotNull('completed_at')
            ->when($resetAt, fn ($q) => $q->where('completed_at', '>=', $resetAt))
            ->orderBy('completed_at', 'desc')
            ->take(10)
            ->get();

        // Active sessions
        $activeSessions = ExamSession::with(['student', 'subject'])
            ->whereNull('completed_at')
            ->when($resetAt, fn ($q) => $q->where('started_at', '>=', $resetAt))
            ->orderBy('started_at', 'desc')
            ->get();

        // Top performers this month
        $topPerformers = ExamSession::with('student')
            ->where('exam_mode', 'jamb')
            ->whereNotNull('completed_at')
            ->when($resetAt, fn ($q) => $q->where('completed_at', '>=', $resetAt))
            ->whereMonth('completed_at', now()->month)
            ->orderBy('score', 'desc')
            ->take(5)
            ->get();

        // Students by subject count
        $studentActivity = User::where('is_admin', false)
            ->withCount(['examSessions' => function ($query) {
                $query->whereNotNull('completed_at');
            }])
            ->withCount(['examSessions as recent_exam_sessions_count' => function ($query) use ($resetAt) {
                $query->whereNotNull('completed_at')
                    ->when($resetAt, fn ($q) => $q->where('completed_at', '>=', $resetAt));
            }])
            ->orderBy($resetAt ? 'recent_exam_sessions_count' : 'exam_sessions_count', 'desc')
            ->take(10)
            ->get();

        // Questions by subject
        $questionsBySubject = Subject::withCount('questions')
            ->orderBy('questions_count', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentExams',
            'activeSessions',
            'topPerformers',
            'studentActivity',
            'questionsBySubject',
            'resetAt'
        ));
    }

    public function allQuestions()
    {
        $questions = Question::with('subject')
            ->orderBy('subject_id')
            ->orderBy('id')
            ->paginate(20);

        return view('admin.all-questions', compact('questions'));
    }

    public function allExams()
    {
        $exams = ExamSession::with(['student', 'subject'])
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->paginate(20);

        return view('admin.all-exams', compact('exams'));
    }
}
