<?php

namespace App\Http\Controllers;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\ExamSubjectScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $resetAt = Cache::get('admin_stats_reset_at');
        $settings = [
            'school_questions_count' => Cache::get('school_questions_count', 40),
            'school_duration_minutes' => Cache::get('school_duration_minutes', 60),
            'jamb_questions_per_subject' => Cache::get('jamb_questions_per_subject', 40),
            'jamb_english_questions' => Cache::get('jamb_english_questions', 60),
            'jamb_duration_minutes' => Cache::get('jamb_duration_minutes', 120),
            'allow_question_flagging' => Cache::get('allow_question_flagging', true),
            'show_results_immediately' => Cache::get('show_results_immediately', true),
            'allow_exam_review' => Cache::get('allow_exam_review', false),
        ];

        return view('admin.settings.index', compact('settings', 'resetAt'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'school_questions_count' => ['required', 'integer', 'min:1', 'max:100'],
            'school_duration_minutes' => ['required', 'integer', 'min:10', 'max:300'],
            'jamb_questions_per_subject' => ['required', 'integer', 'min:1', 'max:100'],
            'jamb_english_questions' => ['required', 'integer', 'min:1', 'max:100'],
            'jamb_duration_minutes' => ['required', 'integer', 'min:30', 'max:300'],
        ]);

        // Handle checkboxes - unchecked boxes don't send data, so set them explicitly
        $validated['allow_question_flagging'] = $request->has('allow_question_flagging');
        $validated['show_results_immediately'] = $request->has('show_results_immediately');
        $validated['allow_exam_review'] = $request->has('allow_exam_review');

        foreach ($validated as $key => $value) {
            Cache::forever($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('status', 'Settings updated successfully!');
    }

    public function clearExamSessions()
    {
        Cache::forever('admin_stats_reset_at', now());

        return redirect()->route('admin.settings.index')
            ->with('status', 'Admin dashboard stats have been reset. History is preserved.');
    }

    public function hardDeleteExamSessions()
    {
        DB::transaction(function () {
            $sessionIds = ExamSession::pluck('id');

            if ($sessionIds->isEmpty()) {
                return;
            }

            ExamSubjectScore::whereIn('exam_session_id', $sessionIds)->delete();
            ExamAnswer::whereIn('exam_session_id', $sessionIds)->delete();
            ExamSession::whereIn('id', $sessionIds)->delete();
        });

        return redirect()->route('admin.settings.index')
            ->with('status', 'All exam sessions have been permanently deleted.');
    }
}
