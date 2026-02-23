@extends('layouts.admin')

@section('title', 'Analytics & Reports')
@section('page-title', 'Analytics & Reports')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-clipboard-data"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $analytics['total_sessions'] }}</h3>
            <p>Total Sessions</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="bi bi-journal-text"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $analytics['school_sessions'] }}</h3>
            <p>School Exams (Avg: {{ $analytics['avg_school_score'] }}%)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="bi bi-lightning-charge"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $analytics['jamb_sessions'] }}</h3>
            <p>JAMB Exams (Avg: {{ number_format($analytics['avg_jamb_score'], 1) }}/400)</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-funnel"></i> Filter Sessions
        </h3>
    </div>
    <form method="GET" action="{{ route('admin.reports.index') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exam_mode" class="form-label">Exam Mode</label>
                    <select id="exam_mode" name="exam_mode" class="form-select">
                        <option value="">All modes</option>
                        <option value="school" {{ $filters['exam_mode'] === 'school' ? 'selected' : '' }}>School</option>
                        <option value="jamb" {{ $filters['exam_mode'] === 'jamb' ? 'selected' : '' }}>JAMB</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select id="subject_id" name="subject_id" class="form-select">
                        <option value="">All subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ (string) $filters['subject_id'] === (string) $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="student_id" class="form-label">Student</label>
                    <select id="student_id" name="student_id" class="form-select">
                        <option value="">All students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ (string) $filters['student_id'] === (string) $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date_from" class="form-label">From Date</label>
                    <input id="date_from" type="date" name="date_from" value="{{ $filters['date_from'] }}" class="form-control">
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date_to" class="form-label">To Date</label>
                    <input id="date_to" type="date" name="date_to" value="{{ $filters['date_to'] }}" class="form-control">
                </div>
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-group d-flex gap-2 w-100">
                    <button class="btn btn-primary flex-1" type="submit">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-light">
                        <i class="bi bi-x"></i> Clear
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-table"></i> Exam Sessions
        </h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mode</th>
                    <th>Student</th>
                    <th>Subject/Info</th>
                    <th>Score</th>
                    <th>Completed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    <tr>
                        <td>
                            <span class="badge {{ $session->exam_mode === 'jamb' ? 'badge-warning' : 'badge-primary' }}">
                                {{ strtoupper($session->exam_mode) }}
                            </span>
                        </td>
                        <td>{{ $session->student->name }}</td>
                        <td>
                            @if($session->exam_mode === 'jamb')
                                <strong>JAMB Mock</strong>
                                <br>
                                <small style="color: #6b7280;">
                                    @foreach($session->examSubjectScores->take(3) as $score)
                                        {{ $score->subject->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if($session->examSubjectScores->count() > 3)
                                        ...
                                    @endif
                                </small>
                            @else
                                {{ $session->subject->name }}
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
                        <td>{{ $session->completed_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.reports.show', $session) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #6b7280;">
                            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            No sessions found matching the filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sessions->hasPages())
        <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
            {{ $sessions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@if($analytics['top_performers']->isNotEmpty())
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-trophy"></i> Top JAMB Performers
        </h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Student</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analytics['top_performers'] as $index => $session)
                    <tr>
                        <td style="font-size: 1.5rem;">
                            @if($index === 0)
                                🥇
                            @elseif($index === 1)
                                🥈
                            @elseif($index === 2)
                                🥉
                            @else
                                <span style="font-size: 1rem; color: #6b7280;">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td><strong>{{ $session->student->name }}</strong></td>
                        <td>
                            <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                {{ number_format($session->score, 1) }} / 400
                            </span>
                        </td>
                        <td>{{ $session->completed_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($analytics['subject_performance']->isNotEmpty())
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-bar-chart"></i> JAMB Subject Performance
        </h3>
        <small style="color: #6b7280;">Ordered from lowest to highest average score</small>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Average Score</th>
                    <th>Attempts</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analytics['subject_performance'] as $subjectPerf)
                    <tr>
                        <td><strong>{{ $subjectPerf->subject->name }}</strong></td>
                        <td style="width: 50%;">
                            <div class="d-flex align-items-center gap-3">
                                <strong style="min-width: 80px;">{{ number_format($subjectPerf->avg_score, 1) }} / 100</strong>
                                <div style="flex: 1; background: #e5e7eb; height: 12px; border-radius: 6px; overflow: hidden;">
                                    <div style="background: {{ $subjectPerf->avg_score >= 70 ? '#10b981' : ($subjectPerf->avg_score >= 50 ? '#f59e0b' : '#ef4444') }}; width: {{ $subjectPerf->avg_score }}%; height: 100%; border-radius: 6px; transition: width 0.3s;"></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-primary">{{ $subjectPerf->attempts }} attempts</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
