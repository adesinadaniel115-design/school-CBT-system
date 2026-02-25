@extends('layouts.admin')

@section('title', 'Exam Settings')
@section('page-title', 'Exam Configuration Settings')

@section('content')
@if(!empty($resetAt))
    <div class="card" style="background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%); border-left: 4px solid #6366f1;">
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="bi bi-arrow-counterclockwise"></i>
                Last stats reset
            </span>
            <span class="text-muted">{{ \Carbon\Carbon::parse($resetAt)->timezone(config('app.timezone'))->format('M d, Y H:i') }}</span>
        </div>
    </div>
@endif
<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <!-- School Mode Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-journal-text"></i> School Mode Settings
                    </h3>
                </div>

                <div class="form-group">
                    <label for="school_questions_count" class="form-label">Number of Questions per Exam</label>
                    <input type="number" id="school_questions_count" name="school_questions_count" class="form-control" value="{{ old('school_questions_count', $settings['school_questions_count']) }}" min="1" max="100" required>
                    <small style="color: #6b7280;">How many questions should appear in each school exam</small>
                </div>

                <div class="form-group">
                    <label for="school_duration_minutes" class="form-label">Exam Duration (Minutes)</label>
                    <input type="number" id="school_duration_minutes" name="school_duration_minutes" class="form-control" value="{{ old('school_duration_minutes', $settings['school_duration_minutes']) }}" min="10" max="300" required>
                    <small style="color: #6b7280;">Time limit for school exams in minutes</small>
                </div>
            </div>

            <!-- JAMB Mode Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-lightning-charge"></i> JAMB Mode Settings
                    </h3>
                </div>

                <div class="form-group">
                    <label for="jamb_english_questions" class="form-label">English Questions Count</label>
                    <input type="number" id="jamb_english_questions" name="jamb_english_questions" class="form-control" value="{{ old('jamb_english_questions', $settings['jamb_english_questions']) }}" min="1" max="100" required>
                    <small style="color: #6b7280;">Number of questions for English (automatic)</small>
                </div>

                <div class="form-group">
                    <label for="jamb_questions_per_subject" class="form-label">Questions per Other Subject</label>
                    <input type="number" id="jamb_questions_per_subject" name="jamb_questions_per_subject" class="form-control" value="{{ old('jamb_questions_per_subject', $settings['jamb_questions_per_subject']) }}" min="1" max="100" required>
                    <small style="color: #6b7280;">Questions for each of the 3 selected subjects</small>
                </div>

                <div class="form-group">
                    <label for="jamb_duration_minutes" class="form-label">JAMB Exam Duration (Minutes)</label>
                    <input type="number" id="jamb_duration_minutes" name="jamb_duration_minutes" class="form-control" value="{{ old('jamb_duration_minutes', $settings['jamb_duration_minutes']) }}" min="30" max="300" required>
                    <small style="color: #6b7280;">Total time limit for JAMB exams (all subjects combined)</small>
                </div>
            </div>

            <!-- General Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-gear-fill"></i> General Settings
                    </h3>
                </div>

                <div class="form-group">
                    <div class="form-check" style="padding: 1rem; background: #f9fafb; border-radius: 8px;">
                        <input type="checkbox" id="allow_question_flagging" name="allow_question_flagging" class="form-check-input" value="1" {{ old('allow_question_flagging', $settings['allow_question_flagging']) ? 'checked' : '' }}>
                        <label for="allow_question_flagging" class="form-check-label">
                            <strong>Allow Question Flagging</strong>
                            <br><small style="color: #6b7280;">Students can flag questions for review</small>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check" style="padding: 1rem; background: #f9fafb; border-radius: 8px;">
                        <input type="checkbox" id="show_results_immediately" name="show_results_immediately" class="form-check-input" value="1" {{ old('show_results_immediately', $settings['show_results_immediately']) ? 'checked' : '' }}>
                        <label for="show_results_immediately" class="form-check-label">
                            <strong>Show Results Immediately</strong>
                            <br><small style="color: #6b7280;">Display exam results right after submission</small>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check" style="padding: 1rem; background: #f9fafb; border-radius: 8px;">
                        <input type="checkbox" id="allow_exam_review" name="allow_exam_review" class="form-check-input" value="1" {{ old('allow_exam_review', $settings['allow_exam_review']) ? 'checked' : '' }}>
                        <label for="allow_exam_review" class="form-check-label">
                            <strong>Allow Exam Review</strong>
                            <br><small style="color: #6b7280;">Students can review their answers after submission</small>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check" style="padding: 1rem; background: #e0f2fe; border-radius: 8px; border-left: 3px solid #0284c7;">
                        <input type="checkbox" id="shuffle_questions" name="shuffle_questions" class="form-check-input" value="1" {{ old('shuffle_questions', $settings['shuffle_questions']) ? 'checked' : '' }}>
                        <label for="shuffle_questions" class="form-check-label">
                            <strong>🔀 Shuffle Questions</strong>
                            <br><small style="color: #6b7280;">Randomize question order for each student (prevents cheating)</small>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check" style="padding: 1rem; background: #fef3c7; border-radius: 8px; border-left: 3px solid #f59e0b;">
                        <input type="checkbox" id="shuffle_options" name="shuffle_options" class="form-check-input" value="1" {{ old('shuffle_options', $settings['shuffle_options']) ? 'checked' : '' }}>
                        <label for="shuffle_options" class="form-check-label">
                            <strong>🔄 Shuffle Answer Options</strong>
                            <br><small style="color: #6b7280;">Randomize A/B/C/D position for each student (prevents answer pattern memorization)</small>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; padding-top: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Settings
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        Reset
                    </button>
                </div>
            </div>
        </form>

        <!-- Maintenance -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-broom"></i> Maintenance
                </h3>
            </div>

            <p class="text-muted mb-3">
                This resets the admin dashboard stats without deleting student history.
            </p>

            <form method="POST" action="{{ route('admin.settings.clear-exam-sessions') }}" onsubmit="return confirm('Reset admin stats? This will not delete student history.');">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Admin Stats
                </button>
            </form>

            <hr class="my-3">

            <p class="text-muted mb-3">
                Permanently delete all exam sessions and answers. This cannot be undone.
            </p>

            <form method="POST" action="{{ route('admin.settings.delete-exam-sessions') }}" onsubmit="return confirm('Permanently delete ALL exam sessions? This will remove all history for every student.');">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash3-fill"></i> Delete All Exam Sessions
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card" style="background: #eff6ff; border-left: 4px solid #3b82f6;">
            <h5 style="color: #1e40af; margin-bottom: 1rem;">
                <i class="bi bi-info-circle-fill"></i> Current Configuration
            </h5>
            <div style="padding: 0.75rem; background: white; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #1f2937; display: block; margin-bottom: 0.25rem;">School Exam</strong>
                <small style="color: #6b7280;">{{ $settings['school_questions_count'] }} questions in {{ $settings['school_duration_minutes'] }} minutes</small>
            </div>
            <div style="padding: 0.75rem; background: white; border-radius: 8px;">
                <strong style="color: #1f2937; display: block; margin-bottom: 0.25rem;">JAMB Exam</strong>
                <small style="color: #6b7280;">
                    {{ $settings['jamb_english_questions'] + ($settings['jamb_questions_per_subject'] * 3) }} total questions 
                    ({{ $settings['jamb_english_questions'] }} English + {{ $settings['jamb_questions_per_subject'] }} × 3 subjects)
                    in {{ $settings['jamb_duration_minutes'] }} minutes
                </small>
            </div>
        </div>

        <div class="card" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
            <h5 style="color: #92400e; margin-bottom: 1rem;">
                <i class="bi bi-exclamation-triangle-fill"></i> Important Notes
            </h5>
            <ul style="color: #92400e; padding-left: 1.25rem; margin: 0; font-size: 0.875rem;">
                <li>Settings take effect immediately for new exams</li>
                <li>Active exams will use their original settings</li>
                <li>Ensure you have enough questions in the database</li>
                <li>Students will see updated durations on dashboard</li>
            </ul>
        </div>
    </div>
</div>
@endsection
