@extends('layouts.admin')

@section('title', 'Add Subject')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}" class="text-decoration-none">Subjects</a></li>
            <li class="breadcrumb-item active">Add Subject</li>
        </ol>
    </nav>
    <h3 class="mb-1">Add New Subject</h3>
    <p class="text-muted small mb-0">Create a new subject for your exam system</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.subjects.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="e.g., Mathematics, English Language"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Enter a unique name for this subject</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Create Subject
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
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body">
                <h5 class="card-title text-primary mb-3">
                    <i class="bi bi-info-circle me-2"></i>Quick Tips
                </h5>
                <ul class="small mb-0 ps-3">
                    <li class="mb-2">Subject names must be unique</li>
                    <li class="mb-2">Use clear, descriptive names</li>
                    <li class="mb-2">Students will see these names when selecting exams</li>
                    <li class="mb-2">You can add questions to this subject later</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
