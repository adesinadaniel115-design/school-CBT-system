@extends('layouts.admin')

@section('title', 'Student Details')
@section('page-title', 'Student Profile')

@section('content')
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                <i class="bi bi-person-badge-fill"></i> {{ $student->name }}
            </h3>
            <p style="color: #6b7280; margin: 0.25rem 0 0; font-size: 0.875rem;">{{ $student->email }}</p>
            @if($student->student_id)
                <span style="display: inline-block; background: #f0f9ff; padding: 0.25rem 0.75rem; border-radius: 6px; color: #0369a1; font-weight: 500; font-size: 0.875rem; margin-top: 0.5rem;">
                    <i class="bi bi-hash"></i> {{ $student->student_id }}
                </span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-secondary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_exams'] }}</h3>
            <p>Total Exams</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="bi bi-journal-text"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['school_exams'] }}</h3>
            <p>School Exams</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="bi bi-lightning-charge"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['jamb_exams'] }}</h3>
            <p>JAMB Exams</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <i class="bi bi-percent"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['avg_school_score'] ? number_format($stats['avg_school_score'], 1) . '%' : 'N/A' }}</h3>
            <p>Avg School Score</p>
        </div>
    </div>
</div>

<!-- Exam History -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-clock-history"></i> Exam History
        </h3>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mode</th>
                    <th>Subject/Info</th>
                    <th>Score</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->examSessions as $session)
                    <tr>
                        <td>
                            <span class="badge {{ $session->exam_mode === 'jamb' ? 'badge-warning' : 'badge-primary' }}">
                                {{ strtoupper($session->exam_mode) }}
                            </span>
                        </td>
                        <td>
                            @if($session->exam_mode === 'jamb')
                                <strong>JAMB Mock</strong>
                                @if($session->examSubjectScores->isNotEmpty())
                                    <br><small style="color: #6b7280;">
                                        @foreach($session->examSubjectScores->take(3) as $score)
                                            {{ $score->subject->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </small>
                                @endif
                            @else
                                {{ $session->subject ? $session->subject->name : 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @if($session->exam_mode === 'jamb')
                                <strong>{{ number_format($session->score, 1) }}</strong> / 400
                            @else
                                <strong>{{ $session->score }}</strong> / {{ $session->total_questions }}
                                <small style="color: #6b7280;">({{ number_format(($session->score / $session->total_questions) * 100, 1) }}%)</small>
                            @endif
                        </td>
                        <td>
                            {{ $session->completed_at->format('M d, Y H:i') }}
                            <br><small style="color: #6b7280;">{{ $session->completed_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.reports.show', $session) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: #6b7280;">
                            <i class="bi bi-inbox" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                            <strong style="display: block; margin-bottom: 0.5rem;">No Exam History</strong>
                            <p style="margin: 0;">This student hasn't taken any exams yet</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
