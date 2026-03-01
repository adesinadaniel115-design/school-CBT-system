@extends('layouts.admin')

@section('title', 'Add New Student')
@section('page-title', 'Add New Student')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-person-plus-fill"></i> Student Information
                </h3>
                <a href="{{ route('admin.students.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="student_id" class="form-label">Student ID <small style="color: #6b7280;">(Leave blank for auto-generate)</small></label>
                    <input type="text" id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id') }}" placeholder="e.g., joy001 or sarah002">
                    <small style="color: #6b7280;">Format: firstname + 3-digit number (e.g., john001, mary002). Auto-generates based on student count.</small>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="center_id" class="form-label">Center (optional)</label>
                    <select id="center_id" name="center_id" class="form-control @error('center_id') is-invalid @enderror">
                        <option value="">-- Select Center --</option>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }} @if($center->location) ({{ $center->location }}) @endif</option>
                        @endforeach
                    </select>
                    @error('center_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    <small style="color: #6b7280;">Minimum 6 characters</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem; padding-top: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Create Student
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card" style="background: #eff6ff; border-left: 4px solid #3b82f6;">
            <h5 style="color: #1e40af; margin-bottom: 1rem;">
                <i class="bi bi-info-circle-fill"></i> Quick Tips
            </h5>
            <ul style="color: #1e40af; padding-left: 1.25rem; margin: 0;">
                <li>The student will use their email to login</li>
                <li>Make sure to use a valid email address</li>
                <li>Students can change their password after first login</li>
                <li>You can edit student details anytime</li>
            </ul>
        </div>
    </div>
</div>
@endsection
