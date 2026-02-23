@extends('layouts.admin')

@section('title', 'Edit Question')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @if(request('subject_id'))
                <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}" class="text-decoration-none">Subjects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.subjects.show', request('subject_id')) }}" class="text-decoration-none">Subject Questions</a></li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}" class="text-decoration-none">Questions</a></li>
            @endif
            <li class="breadcrumb-item active">Edit Question</li>
        </ol>
    </nav>
    <h3 class="mb-1">Edit Question</h3>
    <p class="text-muted small mb-0">Update question details</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.questions.update', $question) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Pass subject_id to return to correct page -->
                    <input type="hidden" name="subject_id" value="{{ request('subject_id', $question->subject_id) }}">
                    
                    @include('admin.questions.partials.form', ['question' => $question])
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Update Question
                        </button>
                        @if(request('subject_id'))
                            <a href="{{ route('admin.subjects.show', request('subject_id')) }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        @else
                            <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-info-circle me-2"></i>Question Info
                </h5>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Question ID</small>
                    <span class="fw-semibold">#{{ $question->id }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Subject</small>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                        {{ $question->subject->name }}
                    </span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Current Difficulty</small>
                    @if($question->difficulty_level === 'easy')
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">Easy</span>
                    @elseif($question->difficulty_level === 'medium')
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Medium</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">Hard</span>
                    @endif
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Created</small>
                    <span class="fw-semibold">{{ $question->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3 mb-0">
            <i class="bi bi-info-circle me-2"></i>
            <small>Updates will not affect completed exams</small>
        </div>
    </div>
</div>
@endsection
