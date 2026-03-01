@extends('layouts.admin')

@section('title', 'Edit Center')
@section('page-title', 'Edit Center')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-pencil-square"></i> Edit Center
                </h3>
                <a href="{{ route('admin.centers.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
            <form method="POST" action="{{ route('admin.centers.update', $center) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Center Name *</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $center->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="location" class="form-label">Location *</label>
                        <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location', $center->location) }}" required>
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $center->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" 
                               class="form-control @error('contact_email') is-invalid @enderror" 
                               value="{{ old('contact_email', $center->contact_email) }}">
                        @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" id="contact_phone" name="contact_phone" 
                               class="form-control @error('contact_phone') is-invalid @enderror" 
                               value="{{ old('contact_phone', $center->contact_phone) }}">
                        @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group mt-3">
                        <p><strong>Students Assigned:</strong> {{ $center->students()->where('is_admin', false)->count() }}</p>
                    </div>
                </div>
                <div class="card-footer" style="display:flex; gap:1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Center
                    </button>
                    <a href="{{ route('admin.centers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
