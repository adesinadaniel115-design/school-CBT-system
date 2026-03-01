@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Statistics Cards -->
<div class="stats-grid">
    <a href="{{ route('admin.students.index') }}" class="stat-card text-decoration-none" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
        <div class="stat-icon blue">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_students'] }}</h3>
            <p>Total Students</p>
        </div>
    </a>
    <a href="{{ route('admin.subjects.index') }}" class="stat-card text-decoration-none" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
        <div class="stat-icon green">
            <i class="bi bi-book-fill"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_subjects'] }}</h3>
            <p>Subjects</p>
        </div>
    </a>
    <a href="{{ route('admin.all-questions') }}" class="stat-card text-decoration-none" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
        <div class="stat-icon yellow">
            <i class="bi bi-question-circle-fill"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_questions'] }}</h3>
            <p>Questions</p>
        </div>
    </a>
    <a href="{{ route('admin.all-exams') }}" class="stat-card text-decoration-none" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
        <div class="stat-icon red">
            <i class="bi bi-clipboard-check-fill"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_exams'] }}</h3>
            <p>Completed Exams</p>
        </div>
    </a>
</div>

<!-- Exam Mode Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #3b82f6;">
            <h5 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">School Mode Exams</h5>
            <h2 style="color: #1f2937; margin: 0;">{{ $stats['school_exams'] }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <h5 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">JAMB Mode Exams</h5>
            <h2 style="color: #1f2937; margin: 0;">{{ $stats['jamb_exams'] }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #10b981;">
            <h5 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Active Sessions</h5>
            <h2 style="color: #1f2937; margin: 0;">{{ $stats['active_exams'] }}</h2>
        </div>
    </div>
</div>

<!-- Active Sessions Alert -->
@if($activeSessions->isNotEmpty())
<div class="card" style="background: #fef3c7; border-left: 4px solid #f59e0b; margin-bottom: 1.5rem;">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem; color: #d97706;"></i>
        <div>
            <h5 style="color: #92400e; margin: 0;">{{ $activeSessions->count() }} Active Exam Session(s)</h5>
            <p style="color: #92400e; margin: 0.25rem 0 0; font-size: 0.875rem;">Students are currently taking exams</p>
        </div>
        <a href="#active-sessions" class="btn btn-warning ms-auto" style="background: #f59e0b;">
            View Active Sessions
        </a>
    </div>
</div>
@endif

<div class="row g-3">
    <!-- Recent Exams -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-clock-history"></i> Recent Exam Activity
                </h3>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-light btn-sm">
                    View All
                </a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Exam Type</th>
                            <th>Score</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentExams as $exam)
                            <tr>
                                <td>
                                    <strong>{{ $exam->student->name }}</strong>
                                    <br><small style="color: #6b7280;">{{ $exam->student->email }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $exam->exam_mode === 'jamb' ? 'badge-warning' : 'badge-primary' }}">
                                        {{ strtoupper($exam->exam_mode) }}
                                    </span>
                                    @if($exam->exam_mode === 'school')
                                        <br><small style="color: #6b7280;">{{ $exam->subject->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($exam->exam_mode === 'jamb')
                                        <strong>{{ number_format($exam->score, 0) }}</strong>/400
                                    @else
                                        <strong>{{ $exam->score }}</strong>/{{ $exam->total_questions }}
                                    @endif
                                </td>
                                <td>{{ $exam->completed_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.reports.show', $exam) }}" class="btn btn-sm btn-light">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #6b7280;">
                                    <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                    No recent exam activity
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Active Sessions -->
        @if($activeSessions->isNotEmpty())
        <div class="card" id="active-sessions">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-play-circle-fill"></i> Active Exam Sessions
                </h3>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Exam Type</th>
                            <th>Started</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeSessions as $session)
                            <tr>
                                <td>
                                    <strong>{{ $session->student->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $session->exam_mode === 'jamb' ? 'badge-warning' : 'badge-primary' }}">
                                        {{ strtoupper($session->exam_mode) }}
                                    </span>
                                    @if($session->exam_mode === 'school')
                                        <br><small style="color: #6b7280;">{{ $session->subject->name }}</small>
                                    @endif
                                </td>
                                <td>{{ $session->started_at->diffForHumans() }}</td>
                                <td>{{ $session->duration_minutes }} mins</td>
                                <td>
                                    <span class="badge badge-success" style="animation: pulse 2s infinite;">
                                        <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> In Progress
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('exam.terminate', $session) }}" style="display:inline;" onsubmit="return confirm('Terminate this exam session?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Terminate exam">
                                            <i class="bi bi-stop-fill"></i> Terminate
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar Stats -->
    <div class="col-lg-4">
        <!-- Top Performers -->
        @if($topPerformers->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-trophy-fill"></i> Top Performers (This Month)
                </h3>
            </div>
            <div style="padding: 0;">
                @foreach($topPerformers as $index => $performer)
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 1rem;">
                        <div style="font-size: 1.5rem;">
                            @if($index === 0) 🥇
                            @elseif($index === 1) 🥈
                            @elseif($index === 2) 🥉
                            @else <span style="font-size: 1rem; color: #6b7280;">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <strong style="display: block; color: #1f2937;">{{ $performer->student->name }}</strong>
                            <small style="color: #6b7280;">{{ number_format($performer->score, 0) }}/400</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Questions by Subject -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-pie-chart-fill"></i> Questions by Subject
                </h3>
            </div>
            <div style="padding: 0;">
                @forelse($questionsBySubject as $subject)
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong style="color: #1f2937;">{{ $subject->name }}</strong>
                            <span class="badge badge-primary">{{ $subject->questions_count }}</span>
                        </div>
                        <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: #4f46e5; width: {{ min(($subject->questions_count / 100) * 100, 100) }}%; height: 100%; transition: width 0.3s;"></div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 2rem; text-align: center; color: #6b7280;">
                        No subjects found
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Most Active Students -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-person-check-fill"></i> Most Active Students
                </h3>
            </div>
            <div style="padding: 0;">
                @forelse($studentActivity as $student)
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: between; align-items: center;">
                        <div style="flex: 1;">
                            <strong style="display: block; color: #1f2937;">{{ $student->name }}</strong>
                            <small style="color: #6b7280;">{{ $student->email }}</small>
                        </div>
                        <span class="badge badge-success">{{ $student->exam_sessions_count }} exams</span>
                    </div>
                @empty
                    <div style="padding: 2rem; text-align: center; color: #6b7280;">
                        No student activity yet
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endsection
