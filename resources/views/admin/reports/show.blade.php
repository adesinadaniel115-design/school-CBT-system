@extends('layouts.admin')

@section('title', 'Session Details')
@section('page-title', 'Exam Session Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-info-circle"></i> Session Information
        </h3>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left"></i> Back to Reports
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <h5 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; margin-bottom: 1rem;">Student Information</h5>
            <div class="mb-3">
                <strong style="color: #6b7280; font-size: 0.875rem;">Name:</strong>
                <div style="font-size: 1.125rem; font-weight: 600; color: #1f2937;">{{ $session->student->name }}</div>
            </div>
            <div class="mb-3">
                <strong style="color: #6b7280; font-size: 0.875rem;">Email:</strong>
                <div style="color: #4f46e5;">{{ $session->student->email }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <h5 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; margin-bottom: 1rem;">Exam Information</h5>
            <div class="mb-3">
                <strong style="color: #6b7280; font-size: 0.875rem;">Mode:</strong>
                <div>
                    <span class="badge {{ $session->exam_mode === 'jamb' ? 'badge-warning' : 'badge-primary' }}" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                        {{ strtoupper($session->exam_mode) }}
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <strong style="color: #6b7280; font-size: 0.875rem;">Questions:</strong>
                <div style="font-weight: 600; color: #1f2937;">{{ $session->total_questions }} questions</div>
            </div>
            <div class="mb-3">
                <strong style="color: #6b7280; font-size: 0.875rem;">Duration:</strong>
                <div style="font-weight: 600; color: #1f2937;">{{ $session->duration_minutes }} minutes</div>
            </div>
            @if($timeTaken)
                <div class="mb-3">
                    <strong style="color: #6b7280; font-size: 0.875rem;">Time Taken:</strong>
                    <div style="font-weight: 600; color: #1f2937;">{{ $timeTaken }} minutes</div>
                </div>
            @endif
        </div>
    </div>

    <hr style="margin: 1.5rem 0; border-color: #e5e7eb;">

    <h5 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; margin-bottom: 1rem;">Completion Timeline</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <strong style="color: #6b7280; font-size: 0.875rem;">Started:</strong>
            <div style="color: #1f2937;">{{ $session->started_at->format('M d, Y H:i:s') }}</div>
        </div>
        <div class="col-md-6">
            <strong style="color: #6b7280; font-size: 0.875rem;">Completed:</strong>
            <div style="color: #1f2937;">{{ $session->completed_at->format('M d, Y H:i:s') }}</div>
        </div>
    </div>
</div>

@if($session->exam_mode === 'jamb')
    <!-- JAMB Mode: Show per-subject scores -->
    <div class="card" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color: white; border: none;">
        <h2 style="color: white; margin-bottom: 0.5rem; font-size: 1.25rem; opacity: 0.95;">Total JAMB Score</h2>
        <h1 style="font-size: 3.5rem; margin: 0; color: white; font-weight: 800;">{{ number_format($session->score, 0) }} / 400</h1>
        <p style="margin-top: 0.5rem; opacity: 0.9; font-size: 1.25rem; font-weight: 600;">{{ number_format(($session->score / 400) * 100, 1) }}%</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-bar-chart-fill"></i> Subject Performance Breakdown
            </h3>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Correct Answers</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($session->examSubjectScores->sortByDesc('score_over_100') as $score)
                        <tr>
                            <td><strong>{{ $score->subject->name }}</strong></td>
                            <td>{{ $score->correct_count }}</td>
                            <td><strong style="color: #4f46e5;">{{ number_format($score->score_over_100, 1) }}</strong> / 100</td>
                            <td>{{ number_format($score->score_over_100, 1) }}%</td>
                            <td>
                                <div style="background: #e5e7eb; height: 28px; border-radius: 8px; position: relative; overflow: hidden;">
                                    <div style="background: {{ $score->score_over_100 >= 70 ? '#10b981' : ($score->score_over_100 >= 50 ? '#f59e0b' : '#ef4444') }}; width: {{ $score->score_over_100 }}%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem; transition: width 0.3s;">
                                        @if($score->score_over_100 >= 70)
                                            Excellent
                                        @elseif($score->score_over_100 >= 50)
                                            Good
                                        @else
                                            Needs Improvement
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($questionBreakdown->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-pie-chart-fill"></i> Question Analysis by Subject
            </h3>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Total Questions</th>
                        <th>Correct</th>
                        <th>Incorrect</th>
                        <th>Unanswered</th>
                        <th>Accuracy</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($questionBreakdown as $breakdown)
                        <tr>
                            <td><strong>{{ $breakdown['subject'] }}</strong></td>
                            <td>{{ $breakdown['total'] }}</td>
                            <td><span class="badge badge-success">{{ $breakdown['correct'] }}</span></td>
                            <td><span class="badge badge-danger">{{ $breakdown['incorrect'] }}</span></td>
                            <td><span style="color: #6b7280;">{{ $breakdown['unanswered'] }}</span></td>
                            <td>
                                @php
                                    $answered = $breakdown['total'] - $breakdown['unanswered'];
                                    $accuracy = $answered > 0 ? ($breakdown['correct'] / $answered) * 100 : 0;
                                @endphp
                                <strong>{{ number_format($accuracy, 1) }}%</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

@else
    <!-- School Mode: Show simple score -->
    <div class="card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none;">
        <h2 style="color: white; margin-bottom: 0.5rem; font-size: 1.25rem; opacity: 0.95;">{{ $session->subject->name }}</h2>
        <h1 style="font-size: 3.5rem; margin: 0; color: white; font-weight: 800;">{{ $session->score }} / {{ $session->total_questions }}</h1>
        <p style="margin-top: 0.5rem; opacity: 0.9; font-size: 1.25rem; font-weight: 600;">{{ number_format(($session->score / $session->total_questions) * 100, 1) }}%</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $session->score }}</h3>
                <p>Correct Answers</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $session->total_questions - $session->score }}</h3>
                <p>Incorrect Answers</p>
            </div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-clipboard-check"></i> Answer Details
        </h3>
    </div>
    
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-question-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $session->total_questions }}</h3>
                <p>Total Questions</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $session->answers->where('is_correct', true)->count() }}</h3>
                <p>Correct</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $session->answers->where('is_correct', false)->where('selected_option', '!=', null)->count() }}</h3>
                <p>Incorrect</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="bi bi-dash-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $session->answers->where('selected_option', null)->count() }}</h3>
                <p>Unanswered</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <strong style="color: #374151; display: block; margin-bottom: 0.5rem;">Overall Accuracy:</strong>
        @php
            $totalAnswered = $session->answers->where('selected_option', '!=', null)->count();
            $correctAnswers = $session->answers->where('is_correct', true)->count();
            $accuracy = $totalAnswered > 0 ? ($correctAnswers / $totalAnswered) * 100 : 0;
        @endphp
        <p style="color: #6b7280; margin-bottom: 1rem;">
            {{ number_format($accuracy, 1) }}% accuracy - {{ $correctAnswers }} correct out of {{ $totalAnswered }} answered
        </p>

        <div style="background: #e5e7eb; height: 24px; border-radius: 12px; overflow: hidden; display: flex;">
            <div style="background: #10b981; width: {{ ($correctAnswers / $session->total_questions) * 100 }}%; transition: width 0.3s;"></div>
            <div style="background: #ef4444; width: {{ ($session->answers->where('is_correct', false)->where('selected_option', '!=', null)->count() / $session->total_questions) * 100 }}%; transition: width 0.3s;"></div>
            <div style="background: #9ca3af; flex: 1;"></div>
        </div>
        
        <div class="d-flex gap-3 mt-2" style="font-size: 0.875rem;">
            <div><span style="display: inline-block; width: 12px; height: 12px; background: #10b981; border-radius: 2px; margin-right: 0.25rem;"></span> Correct</div>
            <div><span style="display: inline-block; width: 12px; height: 12px; background: #ef4444; border-radius: 2px; margin-right: 0.25rem;"></span> Incorrect</div>
            <div><span style="display: inline-block; width: 12px; height: 12px; background: #9ca3af; border-radius: 2px; margin-right: 0.25rem;"></span> Unanswered</div>
        </div>
    </div>
</div>
@endsection
