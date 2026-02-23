@extends('layouts.admin')

@section('title', 'All Students')
@section('page-title', 'All Students')

@section('content')
<div class="mb-4">
    <p class="text-muted">Total: <strong>{{ $students->total() }} students</strong></p>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Exams Completed</th>
                <th>Status</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-weight: bold;">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $student->name }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <small class="text-muted">{{ $student->email }}</small>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $student->exam_sessions_count }} exams</span>
                    </td>
                    <td>
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td>
                        <small class="text-muted">{{ $student->created_at->format('M d, Y') }}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-inbox"></i>
                        <p class="text-muted mb-0 mt-2">No students found</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($students->hasPages())
    <nav class="mt-4">
        {{ $students->links() }}
    </nav>
@endif
@endsection
