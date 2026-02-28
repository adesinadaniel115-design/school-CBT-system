<?php

namespace App\Http\Controllers;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\ExamSubjectScore;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminExportReportController extends Controller
{
    public function index()
    {
        // Students that have at least one completed (submitted) exam session
        $students = User::where('is_admin', false)
            ->whereHas('examSessions', function ($q) {
                $q->whereNotNull('completed_at');
            })
            ->withCount(['examSessions as submitted_exam_sessions_count' => function ($q) {
                $q->whereNotNull('completed_at');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.export_reports.index', compact('students'));
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'integer|exists:users,id',
        ]);

        $students = User::whereIn('id', $data['student_ids'])
            ->where('is_admin', false)
            ->with(['examSessions' => function ($q) {
                $q->whereNotNull('completed_at')
                    ->with(['answers.question.subject', 'examSubjectScores.subject', 'subject'])
                    ->orderByDesc('completed_at');
            }])
            ->get();

        $reports = [];

        foreach ($students as $student) {
            // Aggregate across all completed sessions for this student
            $sessions = $student->examSessions;

            $subjects = collect();
            $totalScore = 0;
            $totalQuestions = 0;
            $correct = 0;
            $wrong = 0;
            $latestSubmittedAt = null;

            foreach ($sessions as $session) {
                $totalScore += (int) $session->score;
                $totalQuestions += (int) $session->total_questions;

                // subjects from examSubjectScores (JAMB) or session subject (school)
                if ($session->examSubjectScores && $session->examSubjectScores->count()) {
                    foreach ($session->examSubjectScores as $ess) {
                        if ($ess->subject) {
                            $subjects->push($ess->subject->name);
                        }
                    }
                } elseif ($session->subject) {
                    $subjects->push($session->subject->name);
                }

                // answers correct/wrong
                if ($session->answers && $session->answers->count()) {
                    $correct += $session->answers->where('is_correct', true)->count();
                    $wrong += $session->answers->where('is_correct', false)->count();
                }

                if ($session->completed_at) {
                    if (!$latestSubmittedAt || $session->completed_at->gt($latestSubmittedAt)) {
                        $latestSubmittedAt = $session->completed_at;
                    }
                }
            }

            $subjects = $subjects->unique()->values();

            $percentage = $totalQuestions > 0 ? round(($totalScore / $totalQuestions) * 100, 2) : 0;

            $reports[] = [
                'student' => $student,
                'subjects' => $subjects,
                'total_score' => $totalScore,
                'percentage' => $percentage,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correct,
                'wrong_answers' => $wrong,
                'date_submitted' => $latestSubmittedAt,
            ];
        }

        // Render a single PDF with each student on its own page.
        // Using Barryvdh\DomPDF facade `PDF`. Ensure package is installed (`composer require barryvdh/laravel-dompdf`).
        try {
            $pdf = app()->make('dompdf.wrapper');
            $html = view('admin.export_reports.report_pdf', compact('reports'))->render();
            $pdf->loadHTML($html)->setPaper('a4', 'portrait');

            return $pdf->stream('exported-exam-reports.pdf');
        } catch (\Throwable $e) {
            Log::error('ExportExamReports PDF generation failed: '.$e->getMessage());
            return back()->with('error', 'Failed to generate PDF. Ensure DomPDF is installed.');
        }
    }
}
