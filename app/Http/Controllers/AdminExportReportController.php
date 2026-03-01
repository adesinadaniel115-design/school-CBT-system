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
use Illuminate\Support\Facades\Schema;

class AdminExportReportController extends Controller
{
    public function index(Request $request)
    {
        // Allow optional center filtering
        $centerId = $request->query('center_id');

        $query = User::where('is_admin', false)
            ->whereHas('examSessions', function ($q) {
                $q->whereNotNull('completed_at');
            })
            ->withCount(['examSessions as submitted_exam_sessions_count' => function ($q) {
                $q->whereNotNull('completed_at');
            }]);

        if ($centerId) {
            $query->where('center_id', $centerId);
        }

        $students = $query->orderBy('name')->get();

        // Only query centers if the table exists to avoid crashing when DB isn't migrated
        if (Schema::hasTable('centers')) {
            $centers = \App\Models\Center::orderBy('name')->get();
        } else {
            $centers = collect();
        }

        return view('admin.export_reports.index', compact('students', 'centers'));
    }

    public function generate(Request $request)
    {
        // Build validation rules; avoid `exists:centers,id` if centers table doesn't exist yet
        $rules = [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'integer|exists:users,id',
            'center_id' => 'nullable|integer',
            'school_name' => 'nullable|string|max:255',
            'school_address' => 'nullable|string|max:500',
            'school_logo' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'watermark_font_size' => 'nullable|integer|min:20|max:120',
        ];

        if (Schema::hasTable('centers')) {
            $rules['center_id'] = 'nullable|integer|exists:centers,id';
        }

        $data = $request->validate($rules);

        $schoolName = $data['school_name'] ?? null;
        $schoolAddress = $data['school_address'] ?? null;
        $watermarkFontSize = $data['watermark_font_size'] ?? 60;
        
        // Handle logo upload
        $schoolLogoBase64 = null;
        if ($request->hasFile('school_logo') && $request->file('school_logo')->isValid()) {
            $logoContent = file_get_contents($request->file('school_logo')->getRealPath());
            $schoolLogoBase64 = 'data:image/' . $request->file('school_logo')->extension() . ';base64,' . base64_encode($logoContent);
        }

        $students = User::whereIn('id', $data['student_ids'])
            ->where('is_admin', false)
            ->with(['examSessions' => function ($q) {
                $q->whereNotNull('completed_at')
                    ->with(['answers.question.subject', 'examSubjectScores.subject', 'subject'])
                    ->orderByDesc('completed_at');
            }])
            ->get();

        $centerName = null;
        if (!empty($data['center_id']) && Schema::hasTable('centers')) {
            $center = \App\Models\Center::find($data['center_id']);
            $centerName = $center?->name;
        }

        $reports = [];

        foreach ($students as $student) {
            // Get the latest JAMB exam session for this student
            $session = $student->examSessions()->where('exam_mode', 'jamb')->latest('completed_at')->first();

            if (!$session) {
                continue;
            }

            // Get 4 subject scores from examSubjectScores (JAMB format)
            $subjectScores = $session->examSubjectScores()
                ->with('subject')
                ->orderBy('subject_id')
                ->limit(4)
                ->get();

            // Calculate total score (sum of individual subject scores)
            $totalScore = $subjectScores->sum('score_over_100');

            // Calculate time spent
            $timeSpent = null;
            if ($session->started_at && $session->completed_at) {
                $timeSpent = $session->started_at->diffInMinutes($session->completed_at);
            }

            // Generate performance remark based on total score
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

            // Generate performance comment based on total score
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
                'student' => $student,
                'subject_scores' => $subjectScores,
                'total_score' => (int) $totalScore,
                'remark' => $remark,
                'comment' => $comment,
                'time_spent' => $timeSpent,
                'completed_at' => $session->completed_at,
            ];
        }

        // Render a single PDF with each student on its own page.
        // Using Barryvdh\DomPDF facade `PDF`. Ensure package is installed (`composer require barryvdh/laravel-dompdf`).
        try {
            $pdf = app()->make('dompdf.wrapper');
            
            // Configure DomPDF options for better compatibility
            $options = $pdf->getOptions();
            $options->set([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Helvetica',
            ]);
            
            $html = view('admin.export_reports.jamb_result_slip', compact('reports', 'schoolName', 'schoolAddress', 'schoolLogoBase64', 'watermarkFontSize', 'centerName'))->render();
            $pdf->loadHTML($html)->setPaper('a4', 'portrait');

            return $pdf->stream('jamb-result-slip.pdf');
        } catch (\Throwable $e) {
            Log::error('ExportExamReports PDF generation failed: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to generate PDF: '.($e->getMessage() ?? 'Unknown error'));
        }
    }
}
