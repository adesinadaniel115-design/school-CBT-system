<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // Get minimum question requirements from cache/config
        $schoolQuestionsCount = Cache::get('school_questions_count', 40);
        $jambQuestionsPerSubject = Cache::get('jamb_questions_per_subject', 3);
        $jambEnglishQuestions = Cache::get('jamb_english_questions', 3);
        $schoolDurationMinutes = Cache::get('school_duration_minutes', 60);
        $jambDurationMinutes = Cache::get('jamb_duration_minutes', 120);
        $jambTotalQuestions = $jambEnglishQuestions + ($jambQuestionsPerSubject * 3);

        // Get all subjects with question counts
        $allSubjects = Subject::withCount('questions')->orderBy('name')->get();

        // Filter subjects for School Mode (need minimum questions)
        $schoolSubjects = $allSubjects->filter(function ($subject) use ($schoolQuestionsCount) {
            return $subject->questions_count >= $schoolQuestionsCount;
        });

        // Filter subjects for JAMB Mode
        $jambSubjects = $allSubjects->filter(function ($subject) use ($jambQuestionsPerSubject, $jambEnglishQuestions) {
            $isEnglish = strtoupper($subject->name) === 'ENGLISH LANGUAGE';
            if ($isEnglish) {
                return $subject->questions_count >= $jambEnglishQuestions;
            }
            return $subject->questions_count >= $jambQuestionsPerSubject;
        })->reject(function ($subject) {
            // Remove English Language from JAMB subject selection (it's automatic)
            return strtoupper($subject->name) === 'ENGLISH LANGUAGE';
        });

        $activeSessions = ExamSession::with('subject')
            ->where('student_id', auth()->id())
            ->whereNull('completed_at')
            ->get();

        return response()->view('student.dashboard', compact(
            'schoolSubjects',
            'jambSubjects',
            'activeSessions',
            'schoolQuestionsCount',
            'schoolDurationMinutes',
            'jambTotalQuestions',
            'jambDurationMinutes'
        ))->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function history(Request $request)
    {
        $sessions = ExamSession::with('subject')
            ->where('student_id', auth()->id())
            ->whereNotNull('completed_at')
            ->where('hidden_by_student', false)
            ->orderByDesc('completed_at')
            ->paginate(10);

        return view('student.history', compact('sessions'));
    }

    public function clearHistory()
    {
        $studentId = auth()->id();

        ExamSession::where('student_id', $studentId)
            ->whereNotNull('completed_at')
            ->update(['hidden_by_student' => true]);

        return redirect()->route('student.history')
            ->with('status', 'Your exam history has been cleared.');
    }

    /**
     * Generate PDF report(s) for the authenticated student's history.
     * Accepts an array of session_ids or the 'all' flag.
     */
    public function generateHistoryPdf(Request $request)
    {
        $request->validate([
            'session_ids' => 'nullable|array',
            'session_ids.*' => 'integer|exists:exam_sessions,id',
            'all' => 'nullable|boolean',
        ]);

        // load center relation in case we need the center name/address
        $user = auth()->user()->load('center');
        $query = $user->examSessions()
            ->whereNotNull('completed_at')
            ->where('hidden_by_student', false);

        // if explicit "all" flag provided, we ignore any individual IDs and export everything
        if ($request->boolean('all')) {
            // leave query as-is
        } elseif ($request->filled('session_ids')) {
            $query->whereIn('id', $request->session_ids);
        }

        $sessions = $query->get();
        if ($sessions->isEmpty()) {
            return back()->with('error', 'No sessions selected for export.');
        }

        // determine school/center info from the user's registration center for header
        $schoolName = null;
        $schoolAddress = null;
        $centerName = null;
        if ($user->center) {
            $schoolName = $user->center->name;
            $centerName = $user->center->name; // treat same for PDF header convenience
            // assume center may have address field
            $schoolAddress = $user->center->address ?? null;
        }

        $reports = [];
        foreach ($sessions as $session) {
            $subjectScores = $session->examSubjectScores()->with('subject')->orderBy('subject_id')->limit(4)->get();
            $totalScore = $subjectScores->sum('score_over_100');
            $timeSpent = null;
            if ($session->started_at && $session->completed_at) {
                $timeSpent = $session->started_at->diffInMinutes($session->completed_at);
            }

            if ($totalScore >= 300) {
                $remark = 'Excellent';
            } elseif ($totalScore >= 240) {
                $remark = 'Very Good';
            } elseif ($totalScore >= 180) {
                $remark = 'Good';
            } elseif ($totalScore >= 120) {
                $remark = 'Average';
            } else {
                $remark = 'Fair';
            }

            if ($totalScore >= 300) {
                $comment = 'Outstanding performance. Excellent grasp of all subjects.';
            } elseif ($totalScore >= 240) {
                $comment = 'Very good performance. Strong understanding across subjects.';
            } elseif ($totalScore >= 180) {
                $comment = 'Good performance. Solid understanding of major concepts.';
            } elseif ($totalScore >= 120) {
                $comment = 'Average performance. Reasonable understanding but needs improvement.';
            } else {
                $comment = 'Below average. Significant improvement needed in studies.';
            }

            $reports[] = [
                'student' => $user,
                'subject_scores' => $subjectScores,
                'total_score' => (int) $totalScore,
                'remark' => $remark,
                'comment' => $comment,
                'time_spent' => $timeSpent,
                'completed_at' => $session->completed_at,
            ];
        }

        $pdf = app()->make('dompdf.wrapper');
        $options = $pdf->getOptions();
        $options->set(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'Helvetica']);
        $html = view('admin.performance.jamb_result_slip', compact(
            'reports',
            'schoolName',
            'schoolAddress',
            'centerName'
        ))->render();
        $pdf->loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->stream('history.pdf');
    }
}
