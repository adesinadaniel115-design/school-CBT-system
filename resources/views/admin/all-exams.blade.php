@extends('layouts.admin')

@section('title', 'All Exams')
@section('page-title', 'All Completed Exams')

@section('content')
<div class="mb-4">
    <p class="text-muted">Total: <strong>{{ $exams->total() }} completed exams</strong></p>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Mode</th>
                <th>Score</th>
                <th>Duration</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams as $exam)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-weight: bold;">
                                {{ strtoupper(substr($exam->student->name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $exam->student->name }}</h6>
                                <small class="text-muted">{{ $exam->student->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $exam->subject->name }}</span>
                    </td>
                    <td>
                        @if($exam->exam_mode === 'school')
                            <span class="badge bg-success">School Exam</span>
                        @else
                            <span class="badge bg-warning text-dark">JAMB Mock</span>
                        @endif
                    </td>
                    <td>
                        <strong>
                            @php
                                $percentage = ($exam->score / $exam->total_questions) * 100;
                                $colorClass = $percentage >= 70 ? 'text-success' : ($percentage >= 50 ? 'text-warning' : 'text-danger');
                            @endphp
                            <span class="{{ $colorClass }}">{{ $exam->score }}/{{ $exam->total_questions }}</span>
                        </strong>
                        <br>
                        <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                    </td>
                    <td>
                        @php
                            $duration = $exam->completed_at->diffInMinutes($exam->started_at);
                            $hours = floor($duration / 60);
                            $minutes = $duration % 60;
                        @endphp
                        @if($hours > 0)
                            {{ $hours }}h {{ $minutes }}m
                        @else
                            {{ $minutes }}m
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">{{ $exam->completed_at->format('M d, Y H:i') }}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-inbox"></i>
                        <p class="text-muted mb-0 mt-2">No exams found</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($exams->hasPages())
    <nav class="mt-4">
        {{ $exams->links() }}
    </nav>
@endif
@endsection
