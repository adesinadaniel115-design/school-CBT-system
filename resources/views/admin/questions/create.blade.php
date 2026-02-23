@extends('layouts.admin')

@section('title', 'Add Question')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}" class="text-decoration-none">Questions</a></li>
            <li class="breadcrumb-item active">Add Question</li>
        </ol>
    </nav>
    <h3 class="mb-1">Add New Question</h3>
    <p class="text-muted small mb-0">Create a new exam question</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.questions.store') }}">
                    @csrf
                    @include('admin.questions.partials.form', ['question' => null])
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Create Question
                        </button>
                        <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body">
                <h5 class="card-title text-primary mb-3">
                    <i class="bi bi-lightbulb me-2"></i>Question Guidelines
                </h5>
                <ul class="small mb-0 ps-3">
                    <li class="mb-2">Write clear, unambiguous questions</li>
                    <li class="mb-2">Ensure all options are plausible</li>
                    <li class="mb-2">Only one correct answer per question</li>
                    <li class="mb-2">Add explanations to help students learn</li>
                    <li class="mb-2">Use appropriate difficulty levels</li>
                </ul>
            </div>
        </div>

        <div class="card border-0 bg-light mt-3">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Difficulty Levels</h6>
                <div class="mb-2">
                    <span class="badge bg-info bg-opacity-10 text-info me-2">Easy</span>
                    <small class="text-muted">Basic concepts</small>
                </div>
                <div class="mb-2">
                    <span class="badge bg-warning bg-opacity-10 text-warning me-2">Medium</span>
                    <small class="text-muted">Moderate challenge</small>
                </div>
                <div>
                    <span class="badge bg-danger bg-opacity-10 text-danger me-2">Hard</span>
                    <small class="text-muted">Advanced topics</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
