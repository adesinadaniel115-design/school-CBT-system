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

        <form method="POST" action="{{ route('admin.export-reports.generate') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="center_select" class="form-label">Center (optional)</label>
                <select id="center_select" class="form-control">
                    <option value="">-- All Centers --</option>
                    @foreach($centers ?? [] as $center)
                        <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }} @if($center->location) ({{ $center->location }}) @endif</option>
                    @endforeach
                </select>
                <input type="hidden" name="center_id" id="center_id_input" value="{{ request('center_id') }}">
            </div>

            <div class="mb-3">
                <label for="school_name" class="form-label">School Name / Institution Name (optional)</label>
                <input type="text" name="school_name" id="school_name" class="form-control" value="{{ old('school_name') }}">
            </div>
            <div class="mb-3">
                <label for="watermark_font_size" class="form-label">Watermark Font Size (optional, default: 60px)</label>
                <input type="number" name="watermark_font_size" id="watermark_font_size" class="form-control" min="20" max="120" value="{{ old('watermark_font_size', 60) }}">
                <small class="form-text text-muted">Adjust the font size of the school name watermark (20-120px)</small>
            </div>
            <div class="mb-3">
                <label for="school_address" class="form-label">School Address (optional)</label>
                <input type="text" name="school_address" id="school_address" class="form-control" value="{{ old('school_address') }}">
            </div>
            <div class="mb-3">
                <label for="school_logo" class="form-label">School Logo (optional)</label>
                <input type="file" name="school_logo" id="school_logo" class="form-control" accept="image/*">
                <small class="form-text text-muted">Max 2MB. Accepted formats: JPG, PNG, GIF</small>
            </div>
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
    // Fetch students by center
    document.getElementById('center_select')?.addEventListener('change', function (e) {
        const centerId = e.target.value;
        // update hidden field so it's submitted
        document.getElementById('center_id_input').value = centerId;
        const tbody = document.querySelector('table tbody');
        tbody.innerHTML = '<tr><td colspan="4">Loading...</td></tr>';
        if (!centerId) {
            // reload the page to show all students
            window.location.href = '{{ route("admin.export-reports.index") }}';
            return;
        }

        fetch('{{ url("/admin/centers") }}' + '/' + centerId + '/students')
            .then(r => r.json())
            .then(data => {
                const students = data.students || [];
                if (students.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4">No students found for selected center.</td></tr>';
                    return;
                }
                tbody.innerHTML = students.map(s => `
                    <tr>
                        <td><input type="checkbox" name="student_ids[]" value="${s.id}"></td>
                        <td>${s.name}</td>
                        <td>${s.student_id || '-'}</td>
                        <td><span class="badge badge-primary">-</span></td>
                    </tr>
                `).join('');
            }).catch(() => {
                tbody.innerHTML = '<tr><td colspan="4">Failed to load students.</td></tr>';
            });
    });
</script>
@endpush

@endsection
