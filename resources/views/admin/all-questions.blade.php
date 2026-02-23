@extends('layouts.admin')

@section('title', 'All Questions')
@section('page-title', 'All Questions Bank')

@section('content')
<div class="mb-4">
    <p class="text-muted">Total: <strong>{{ $questions->total() }} questions</strong></p>
</div>

@forelse($questions as $question)
    <div class="card border-start border-start-3 border-primary mb-3 shadow-sm">
        <div class="card-body">
            <!-- Question Header -->
            <div class="row align-items-start mb-3">
                <div class="col">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary rounded-pill">{{ $question->subject->name }}</span>
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
            <div class="alert alert-info alert-sm mb-0">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Correct Answer:</strong> <span class="badge bg-success text-white">{{ $question->correct_option }}</span>
                    </div>
                    @if($question->explanation)
                        <div class="col-md-8">
                            <strong>Explanation:</strong> 
                            <p class="mb-0 mt-2">{{ $question->explanation }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-warning text-center py-5">
        <i class="bi bi-inbox"></i>
        <p class="mb-0 mt-2">No questions found</p>
    </div>
@endforelse

<!-- Pagination -->
@if($questions->hasPages())
    <nav class="mt-4">
        {{ $questions->links() }}
    </nav>
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
