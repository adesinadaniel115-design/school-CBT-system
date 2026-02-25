@extends('layouts.admin')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-pencil-fill"></i> Edit Student Information
                </h3>
                <a href="{{ route('admin.students.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <form method="POST" action="{{ route('admin.students.update', $student) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id', $student->student_id) }}" placeholder="e.g., joy001 or sarah002">
                    <small style="color: #6b7280;">Format: firstname + 3-digit number</small>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password (Optional)</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    <small style="color: #6b7280;">Leave blank to keep current password</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem; padding-top: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Student
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <h5 style="color: #1f2937; margin-bottom: 1rem;">
                <i class="bi bi-person-badge"></i> Student Details
            </h5>
            <div style="padding: 1rem; background: #f0f9ff; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #0369a1;">
                <small style="color: #6b7280; display: block;">Student ID</small>
                <strong style="color: #1f2937; font-size: 1.1rem;">{{ $student->student_id ?? 'Not assigned' }}</strong>
            </div>
            <div style="padding: 1rem; background: #f9fafb; border-radius: 8px; margin-bottom: 1rem;">
                <small style="color: #6b7280; display: block;">Registered</small>
                <strong style="color: #1f2937;">{{ $student->created_at->format('M d, Y') }}</strong>
            </div>
            <div style="padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <small style="color: #6b7280; display: block;">Exams Taken</small>
                <strong style="color: #1f2937;">{{ $student->examSessions()->count() }} exams</strong>
            </div>
        </div>
    </div>
</div>
@endsection
