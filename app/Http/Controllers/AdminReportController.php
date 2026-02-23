<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\ExamSubjectScore;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $students = User::where('is_admin', false)->orderBy('name')->get();
        
        // Get filter parameters
        $filters = [
            'exam_mode' => $request->input('exam_mode'),
            'subject_id' => $request->input('subject_id'),
            'student_id' => $request->input('student_id'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        // Build query with eager loading
        $query = ExamSession::with(['student', 'subject', 'examSubjectScores.subject'])
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at');

        // Apply filters
        if ($filters['exam_mode']) {
            $query->where('exam_mode', $filters['exam_mode']);
        }

        if ($filters['subject_id']) {
            if ($request->input('exam_mode') === 'jamb') {
                // For JAMB, filter by sessions that have this subject in their scores
                $query->whereHas('examSubjectScores', function ($q) use ($filters) {
                    $q->where('subject_id', $filters['subject_id']);
                });
            } else {
                $query->where('subject_id', $filters['subject_id']);
            }
        }

        if ($filters['student_id']) {
            $query->where('student_id', $filters['student_id']);
        }

        if ($filters['date_from']) {
            $query->whereDate('completed_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to']) {
            $query->whereDate('completed_at', '<=', $filters['date_to']);
        }

        // Paginate results
        $sessions = $query->paginate(15)->withQueryString();

        // Get analytics
        $analytics = $this->getAnalytics($filters);

        return view('admin.reports.index', compact('sessions', 'subjects', 'students', 'filters', 'analytics'));
    }

    public function show(ExamSession $session)
    {
        // Load all relationships for detailed view
        $session->load([
            'student',
            'subject',
            'examSubjectScores.subject',
            'answers.question.subject'
        ]);

        // Calculate time taken
        $timeTaken = null;
        if ($session->started_at && $session->completed_at) {
            $timeTaken = $session->started_at->diffInMinutes($session->completed_at);
        }

        // Get question breakdown by subject (for JAMB)
        $questionBreakdown = [];
        if ($session->exam_mode === 'jamb') {
            $questionBreakdown = $session->answers()
                ->with('question.subject')
                ->get()
                ->groupBy(function ($answer) {
                    return $answer->question->subject->name;
                })
                ->map(function ($answers, $subjectName) {
                    return [
                        'subject' => $subjectName,
                        'total' => $answers->count(),
                        'correct' => $answers->where('is_correct', true)->count(),
                        'incorrect' => $answers->where('is_correct', false)->count(),
                        'unanswered' => $answers->where('selected_option', null)->count(),
                    ];
                });
        }

        return view('admin.reports.show', compact('session', 'timeTaken', 'questionBreakdown'));
    }

    private function getAnalytics(array $filters)
    {
        $analytics = [
            'total_sessions' => 0,
            'school_sessions' => 0,
            'jamb_sessions' => 0,
            'avg_school_score' => 0,
            'avg_jamb_score' => 0,
            'top_performers' => collect(),
            'subject_performance' => collect(),
        ];

        // Build base query for analytics
        $query = ExamSession::whereNotNull('completed_at');

        if ($filters['date_from']) {
            $query->whereDate('completed_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to']) {
            $query->whereDate('completed_at', '<=', $filters['date_to']);
        }

        // Total sessions
        $analytics['total_sessions'] = $query->count();
        $analytics['school_sessions'] = (clone $query)->where('exam_mode', 'school')->count();
        $analytics['jamb_sessions'] = (clone $query)->where('exam_mode', 'jamb')->count();

        // Average scores
        $schoolAvg = (clone $query)->where('exam_mode', 'school')
            ->selectRaw('AVG(score / total_questions * 100) as avg_percentage')
            ->first();
        $analytics['avg_school_score'] = $schoolAvg ? round($schoolAvg->avg_percentage, 2) : 0;

        $jambAvg = (clone $query)->where('exam_mode', 'jamb')
            ->selectRaw('AVG(score) as avg_score')
            ->first();
        $analytics['avg_jamb_score'] = $jambAvg ? round($jambAvg->avg_score, 2) : 0;

        // Top performers (JAMB only, with filter consideration)
        if (!$filters['exam_mode'] || $filters['exam_mode'] === 'jamb') {
            $topPerformersQuery = ExamSession::with('student')
                ->where('exam_mode', 'jamb')
                ->whereNotNull('completed_at')
                ->orderByDesc('score')
                ->limit(10);

            if ($filters['date_from']) {
                $topPerformersQuery->whereDate('completed_at', '>=', $filters['date_from']);
            }

            if ($filters['date_to']) {
                $topPerformersQuery->whereDate('completed_at', '<=', $filters['date_to']);
            }

            $analytics['top_performers'] = $topPerformersQuery->get();
        }

        // Subject performance (most failed subjects in JAMB)
        if (!$filters['exam_mode'] || $filters['exam_mode'] === 'jamb') {
            $subjectPerfQuery = ExamSubjectScore::with('subject')
                ->select('subject_id', DB::raw('AVG(score_over_100) as avg_score'), DB::raw('COUNT(*) as attempts'))
                ->groupBy('subject_id')
                ->orderBy('avg_score', 'asc')
                ->limit(10);

            // Apply date filters through exam_session relationship
            if ($filters['date_from'] || $filters['date_to']) {
                $subjectPerfQuery->whereHas('examSession', function ($q) use ($filters) {
                    if ($filters['date_from']) {
                        $q->whereDate('completed_at', '>=', $filters['date_from']);
                    }
                    if ($filters['date_to']) {
                        $q->whereDate('completed_at', '<=', $filters['date_to']);
                    }
                });
            }

            $analytics['subject_performance'] = $subjectPerfQuery->get();
        }

        return $analytics;
    }
}
