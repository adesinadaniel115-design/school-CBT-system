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
        $schoolQuestionsCount = Cache::get('school_questions_count', 3);
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
}
