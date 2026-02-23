@extends('layouts.admin')

@section('title', 'Questions')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div class="mb-3 mb-md-0">
        <h3 class="mb-1">Questions Bank</h3>
        <p class="text-muted small mb-0">Manage exam questions for all subjects</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.questions.import.form') }}" class="btn btn-outline-primary">
            <i class="bi bi-cloud-upload me-2"></i>Import CSV
        </a>
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add Question
        </a>
    </div>
</div>

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.questions.index') }}" class="row g-3">
            <div class="col-md-10">
                <label for="subject_id" class="form-label small fw-semibold">Filter by Subject</label>
                <select id="subject_id" name="subject_id" class="form-select">
                    <option value="">All Subjects ({{ $questions->total() }} questions)</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ (string) $subjectId === (string) $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }} ({{ $subject->questions()->count() }} questions)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($questions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3" style="width: 80px;">ID</th>
                            <th class="py-3" style="width: 150px;">Subject</th>
                            <th class="py-3">Question</th>
                            <th class="py-3 text-center" style="width: 100px;">Correct</th>
                            <th class="py-3 text-center" style="width: 120px;">Difficulty</th>
                            <th class="py-3 text-end" style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $question)
                            <tr>
                                <td class="px-4 text-muted small">#{{ $question->id }}</td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ $question->subject->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 400px;" title="{{ $question->question_text }}">
                                        {{ \Illuminate\Support\Str::limit($question->question_text, 80) }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success px-3 py-2">{{ $question->correct_option }}</span>
                                </td>
                                <td class="text-center">
                                    @if($question->difficulty_level === 'easy')
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">Easy</span>
                                    @elseif($question->difficulty_level === 'medium')
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Medium</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">Hard</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" class="d-inline" onsubmit="return confirm('Delete this question permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($questions->hasPages())
                <div class="mt-4">
                    {{ $questions->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-question-circle display-1 text-muted mb-3"></i>
                <h5 class="text-muted">No questions found</h5>
                <p class="text-muted mb-4">
                    @if($subjectId)
                        No questions for this subject yet
                    @else
                        Start building your question bank
                    @endif
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Question
                    </a>
                    <a href="{{ route('admin.questions.import.form') }}" class="btn btn-outline-primary">
                        <i class="bi bi-cloud-upload me-2"></i>Import CSV
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
