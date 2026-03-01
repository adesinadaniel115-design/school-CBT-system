@extends('layouts.admin')

@section('title', 'Centers Management')
@section('page-title', 'Centers Management')

@section('content')
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="card-title mb-0">🏫 Centers Management</h3>
            <small class="text-muted">Manage JAMB UTME simulation centers</small>
        </div>
        <a href="{{ route('admin.centers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Center
        </a>
    </div>
</div>

<!-- Search Bar -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.centers.index') }}" class="d-flex gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search centers by name or location..." 
                class="form-control"
            >
            <button type="submit" class="btn btn-secondary">
                Search
            </button>
        </form>
    </div>
</div>

@if(session('status'))
    <div class="alert alert-success">✓ {{ session('status') }}</div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Center Name</th>
                    <th>Location</th>
                    <th>Students</th>
                    <th>Contact Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centers as $center)
                    <tr>
                        <td>
                            <strong>{{ $center->name }}</strong><br>
                            @if($center->description)
                                <small class="text-muted">{{ Str::limit($center->description, 40) }}</small>
                            @endif
                        </td>
                        <td>{{ $center->location }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $center->students_count }}
                            </span>
                        </td>
                        <td>{{ $center->contact_email ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.centers.edit', $center) }}" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.centers.destroy', $center) }}" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this center? Students assigned to it will not be deleted.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <p class="mb-1">No centers found</p>
                            <small>Create a new center to get started</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($centers->hasPages())
    <div class="mt-3">
        {{ $centers->links() }}
    </div>
@endif
@endsection
