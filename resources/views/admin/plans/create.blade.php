@extends('layouts.admin')

@section('title', 'New Plan')
@section('page-title', 'Create Plan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-plus-circle"></i> Create Plan</h3>
        <a href="{{ route('admin.plans.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.plans.store') }}">
            @csrf

            <div class="form-group mb-3">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Price (NGN) *</label>
                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Allowed Attempts *</label>
                <input type="number" name="attempts_allowed" class="form-control @error('attempts_allowed') is-invalid @enderror" value="{{ old('attempts_allowed') }}" min="0" required>
                @error('attempts_allowed')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Duration (days)</label>
                <input type="number" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror" value="{{ old('duration_days') }}" min="0">
                @error('duration_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Leave empty for no expiration.</small>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">School questions count</label>
                <input type="number" name="school_questions" class="form-control @error('school_questions') is-invalid @enderror" value="{{ old('school_questions') }}" min="0">
                @error('school_questions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Leave empty to use default/cached value.</small>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">JAMB questions per subject</label>
                <input type="number" name="jamb_questions_per_subject" class="form-control @error('jamb_questions_per_subject') is-invalid @enderror" value="{{ old('jamb_questions_per_subject') }}" min="0">
                @error('jamb_questions_per_subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">JAMB english question count</label>
                <input type="number" name="jamb_english_questions" class="form-control @error('jamb_english_questions') is-invalid @enderror" value="{{ old('jamb_english_questions') }}" min="0">
                @error('jamb_english_questions')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" name="has_explanations" id="has_explanations" value="1" class="form-check-input" {{ old('has_explanations') ? 'checked' : '' }}>
                <label class="form-check-label" for="has_explanations">Includes explanations</label>
            </div>
            <div class="form-check mb-2">
                <input type="checkbox" name="has_leaderboard" id="has_leaderboard" value="1" class="form-check-input" {{ old('has_leaderboard') ? 'checked' : '' }}>
                <label class="form-check-label" for="has_leaderboard">Allows leaderboard</label>
            </div>
            <div class="form-check mb-2">
                <input type="checkbox" name="has_streak" id="has_streak" value="1" class="form-check-input" {{ old('has_streak') ? 'checked' : '' }}>
                <label class="form-check-label" for="has_streak">Tracks streak</label>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Plan</button>
                <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
