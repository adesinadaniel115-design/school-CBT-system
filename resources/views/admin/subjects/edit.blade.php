@extends('layouts.admin')

@section('title', 'Edit Subject')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}" class="text-decoration-none">Subjects</a></li>
            <li class="breadcrumb-item active">Edit Subject</li>
        </ol>
    </nav>
    <h3 class="mb-1">Edit Subject</h3>
    <p class="text-muted small mb-0">Update subject information</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $subject->name) }}" 
                               placeholder="e.g., Mathematics, English Language"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Enter a unique name for this subject</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Update Subject
                        </button>
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-info-circle me-2"></i>Subject Information
                </h5>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Subject ID</small>
                    <span class="fw-semibold">#{{ $subject->id }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Total Questions</small>
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                        {{ $subject->questions()->count() }} questions
                    </span>
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Created</small>
                    <span class="fw-semibold">{{ $subject->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="alert alert-warning mt-3 mb-0">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <small><strong>Note:</strong> Changing the subject name will update it system-wide</small>
        </div>
    </div>
</div>
@endsection
