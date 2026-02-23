@extends('layouts.admin')

@section('title', 'Student Management')
@section('page-title', 'Student Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-people-fill"></i> All Students
        </h3>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Student
        </a>
    </div>

    <!-- Search -->
    <form method="GET" action="{{ route('admin.students.index') }}" class="mb-3">
        <div class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </div>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Exams Taken</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $students->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $student->name }}</strong></td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <span class="badge badge-primary">{{ $student->exam_sessions_count }} exams</span>
                        </td>
                        <td>{{ $student->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.students.destroy', $student) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student? All their exam records will be deleted.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #6b7280;">
                            <i class="bi bi-inbox" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                            <strong style="display: block; margin-bottom: 0.5rem;">No students found</strong>
                            <p style="margin: 0;">Add your first student to get started</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
        <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
