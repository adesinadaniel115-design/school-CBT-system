@extends('layouts.admin')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-person-circle"></i> Profile Settings
        </h3>
    </div>

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-md-5">
                <h6 class="fw-semibold mb-3">Profile Photo</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-avatar" style="width: 72px; height: 72px;">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile photo" class="user-avatar-img">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/png,image/jpeg">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">PNG or JPG, max 2MB.</small>
                        @if($user->profile_photo_path)
                            <div class="mt-2">
                                <button type="submit" name="remove_photo" value="1" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Remove Photo
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <h6 class="fw-semibold mb-3">Change Password</h6>
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="New password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Save Changes
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
