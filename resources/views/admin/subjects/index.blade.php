@extends('layouts.admin')

@section('title', 'Subjects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Subjects Management</h3>
        <p class="text-muted small mb-0">Manage exam subjects available to students</p>
    </div>
    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Subject
    </a>
</div>

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($subjects->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="py-3">Subject Name</th>
                            <th class="py-3 text-center">Questions</th>
                            <th class="py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                            <tr>
                                <td class="px-4 text-muted">#{{ $subject->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                            <i class="bi bi-book fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $subject->name }}</div>
                                            <small class="text-muted">Subject ID: {{ $subject->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.subjects.show', $subject) }}" class="badge bg-info bg-opacity-10 text-info px-3 py-2 text-decoration-none" style="cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='rgba(13, 110, 253, 0.15)'; this.style.transform='scale(1.05)';" onmouseout="this.style.backgroundColor='rgba(13, 110, 253, 0.1)'; this.style.transform='scale(1)';">
                                        {{ $subject->questions()->count() }} questions
                                    </a>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" class="d-inline" onsubmit="return confirm('Delete this subject? All related questions will also be deleted.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($subjects->hasPages())
                <div class="mt-4">
                    {{ $subjects->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                <h5 class="text-muted">No subjects yet</h5>
                <p class="text-muted mb-4">Start by adding your first subject</p>
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add First Subject
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
