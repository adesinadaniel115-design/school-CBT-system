@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Export Exam Reports</h3>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.export-reports.generate') }}">
            @csrf

            <div class="mb-3">
                <button type="submit" class="btn btn-primary" id="generate-btn">Generate PDF</button>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:40px;"><input type="checkbox" id="select-all"></th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Submitted Exams</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td><input type="checkbox" name="student_ids[]" value="{{ $student->id }}"></td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->student_id ?? '-' }}</td>
                            <td><span class="badge badge-primary">{{ $student->submitted_exam_sessions_count }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No submitted students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('select-all')?.addEventListener('change', function (e) {
        const checked = e.target.checked;
        document.querySelectorAll('input[name="student_ids[]"]').forEach(cb => cb.checked = checked);
    });
</script>
@endpush

@endsection
