@extends('layouts.admin')

@section('title', $subject->name . ' - Questions')
@section('page-title', $subject->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">{{ $subject->name }}</h3>
        <p class="text-muted small mb-0">All questions assigned to this subject</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-2"></i>Edit Subject
        </a>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Subjects
        </a>
    </div>
</div>

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Subject Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title small text-muted">Total Questions</h5>
                <h2 class="display-4 text-primary">{{ $questions->total() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title small text-muted">Difficulty Levels</h5>
                <p class="mb-0">
                    <span class="badge bg-success me-1">Easy</span>
                    <span class="badge bg-warning text-dark me-1">Medium</span>
                    <span class="badge bg-danger">Hard</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title small text-muted">Last Updated</h5>
                <p class="mb-0">{{ \Carbon\Carbon::now()->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title small text-muted">Subject ID</h5>
                <p class="mb-0 text-muted">#{{ $subject->id }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Questions List -->
@if($questions->count() > 0)
    @foreach($questions as $question)
        <div class="card border-start border-start-3 border-primary mb-3 shadow-sm">
            <div class="card-body">
                <!-- Question Header -->
                <div class="row align-items-start mb-3">
                    <div class="col">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-secondary">ID: {{ $question->id }}</span>
                            @if($question->difficulty_level)
                                @if($question->difficulty_level === 'easy')
                                    <span class="badge bg-success">{{ ucfirst($question->difficulty_level) }}</span>
                                @elseif($question->difficulty_level === 'medium')
                                    <span class="badge bg-warning text-dark">{{ ucfirst($question->difficulty_level) }}</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($question->difficulty_level) }}</span>
                                @endif
                            @endif
                        </div>
                        <h6 class="fw-semibold mb-0">{{ $question->question_text }}</h6>
                    </div>
                </div>

                <!-- Options Grid -->
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <div class="p-2 border rounded {{ $question->correct_option === 'A' ? 'border-success border-2 bg-success bg-opacity-5' : '' }}">
                            <strong>A.</strong> {{ $question->option_a }}
                            @if($question->correct_option === 'A')
                                <i class="bi bi-check-circle-fill text-success float-end"></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 border rounded {{ $question->correct_option === 'B' ? 'border-success border-2 bg-success bg-opacity-5' : '' }}">
                            <strong>B.</strong> {{ $question->option_b }}
                            @if($question->correct_option === 'B')
                                <i class="bi bi-check-circle-fill text-success float-end"></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 border rounded {{ $question->correct_option === 'C' ? 'border-success border-2 bg-success bg-opacity-5' : '' }}">
                            <strong>C.</strong> {{ $question->option_c }}
                            @if($question->correct_option === 'C')
                                <i class="bi bi-check-circle-fill text-success float-end"></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 border rounded {{ $question->correct_option === 'D' ? 'border-success border-2 bg-success bg-opacity-5' : '' }}">
                            <strong>D.</strong> {{ $question->option_d }}
                            @if($question->correct_option === 'D')
                                <i class="bi bi-check-circle-fill text-success float-end"></i>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Correct Answer & Explanation -->
                <div class="alert alert-info alert-sm mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Correct Answer:</strong> <span class="badge bg-success text-white">{{ $question->correct_option }}</span>
                        </div>
                        @if($question->explanation)
                            <div class="col-md-9">
                                <strong>Explanation:</strong> 
                                <p class="mb-0 mt-2">{{ $question->explanation }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.questions.edit', $question) }}?subject_id={{ $subject->id }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" class="d-inline" onsubmit="return confirm('Delete this question?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    @if($questions->hasPages())
        <nav class="mt-4">
            {{ $questions->links() }}
        </nav>
    @endif
@else
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
        <h5 class="mt-2">No questions yet</h5>
        <p class="text-muted mb-0">Start by adding questions to this subject</p>
    </div>
@endif

<style>
    .alert-sm {
        padding: 0.75rem 1rem;
        margin-bottom: 0;
    }
    
    .alert-sm .row {
        gap: 1.5rem;
    }
    
    .alert-sm strong {
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .border-start-3 {
        border-left-width: 4px !important;
    }
</style>
@endsection
