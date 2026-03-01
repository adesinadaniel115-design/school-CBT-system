@extends('layouts.admin')

@section('title', 'Create Center')
@section('page-title', 'Create New Center')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-building"></i> New Center
                </h3>
                <a href="{{ route('admin.centers.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
            <form method="POST" action="{{ route('admin.centers.store') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Center Name *</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="e.g., Fortress CBT Center, Lagos Mainland Center" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="location" class="form-label">Location *</label>
                        <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location') }}" placeholder="e.g., Lagos, Abuja, Nigeria" required>
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Add a brief description about this center...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" 
                               class="form-control @error('contact_email') is-invalid @enderror" 
                               value="{{ old('contact_email') }}" placeholder="center@example.com">
                        @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" id="contact_phone" name="contact_phone" 
                               class="form-control @error('contact_phone') is-invalid @enderror" 
                               value="{{ old('contact_phone') }}" placeholder="+234 (0) 123 456 7890">
                        @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer" style="display:flex; gap:1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Create Center
                    </button>
                    <a href="{{ route('admin.centers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
