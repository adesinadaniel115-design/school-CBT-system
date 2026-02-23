@extends('layouts.admin')

@section('title', 'All Subjects')
@section('page-title', 'All Subjects')

@section('content')
<div class="mb-4">
    <p class="text-muted">Total: <strong>{{ $subjects->total() }} subjects</strong></p>
</div>

<div class="row g-3">
    @forelse($subjects as $subject)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" 
                 onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'" 
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; text-align: center;">
                    <i class="bi bi-book-fill" style="font-size: 2.5rem;"></i>
                    <h5 class="mt-2 mb-0">{{ $subject->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total Questions</span>
                        <span class="badge bg-primary rounded-pill" style="font-size: 1.1rem; padding: 0.5rem 0.75rem;">{{ $subject->questions_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center py-5">
                <i class="bi bi-inbox"></i>
                <p class="mb-0 mt-2">No subjects found</p>
            </div>
        </div>
    @endforelse
</div>

@if($subjects->hasPages())
    <nav class="mt-4">
        {{ $subjects->links() }}
    </nav>
@endif
@endsection
