@extends('layouts.admin')

@section('content')

<!-- Filter Section -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-funnel"></i> Filter Sessions</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.performance.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" id="date_from" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" id="date_to" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    <small class="form-text text-muted">Leave blank for all dates. If From Date only, shows that exact day.</small>
                </div>
                <div class="col-md-4">
                    <label for="center_filter" class="form-label">Center</label>
                    <select id="center_filter" name="center_id" class="form-control">
                        <option value="">-- All Centers --</option>
                        @foreach($centers ?? [] as $center)
                            <option value="{{ $center->id }}" {{ ($filters['center_id'] ?? '') == $center->id ? 'selected' : '' }}>
                                {{ $center->name }}
                                @if($center->location) ({{ $center->location }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.performance.index') }}" class="btn btn-light">
                        <i class="bi bi-x"></i> Clear All
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Summary -->
@if($stats['total_sessions'] > 0 || ($filters['date_from'] || $filters['date_to'] || $filters['center_id']))
<div class="row" style="margin-top: 1.5rem;">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">JAMB SESSIONS</div>
                <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">{{ $stats['total_sessions'] }}</div>
                <div style="font-size: 0.8rem; color: #9ca3af; margin-top: 0.5rem;">{{ $stats['date_range'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">AVG SCORE</div>
                <div style="font-size: 2rem; font-weight: 700; color: #3b82f6;">{{ $stats['avg_score'] }}/400</div>
                <div style="font-size: 0.8rem; color: #9ca3af; margin-top: 0.5rem;">Mean performance</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">HIGHEST SCORE</div>
                <div style="font-size: 2rem; font-weight: 700; color: #10b981;">{{ $stats['highest_score'] }}/400</div>
                <div style="font-size: 0.8rem; color: #9ca3af; margin-top: 0.5rem;">Best attempt</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">UNIQUE STUDENTS</div>
                <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">{{ $students->count() }}</div>
                <div style="font-size: 0.8rem; color: #9ca3af; margin-top: 0.5rem;">Completed JAMB</div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Export & Student Selection -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-people-fill"></i> Select Students for Export</h3>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle-fill"></i> <strong>Export Details:</strong> Each selected student's <strong>all JAMB sessions</strong> will be exported. If a student retook the exam, all attempts appear in one PDF.
        </div>

        @if($students->isNotEmpty())
        <form method="POST" action="{{ route('admin.performance.generate') }}" enctype="multipart/form-data">
            @csrf

            <!-- Hidden filters to preserve during export -->
            <input type="hidden" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
            <input type="hidden" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
            <input type="hidden" name="center_id" value="{{ $filters['center_id'] ?? '' }}">

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="school_name" class="form-label">School Name / Institution Name (optional)</label>
                    <input type="text" name="school_name" id="school_name" class="form-control" value="{{ old('school_name') }}" placeholder="e.g., Federal College of Education">
                </div>
                <div class="col-md-4">
                    <label for="watermark_font_size" class="form-label">Watermark Font Size (optional)</label>
                    <input type="number" name="watermark_font_size" id="watermark_font_size" class="form-control" min="20" max="120" value="{{ old('watermark_font_size', 60) }}">
                    <small class="form-text text-muted">20-120px (default: 60)</small>
                </div>
                <div class="col-md-4">
                    <label for="school_address" class="form-label">School Address (optional)</label>
                    <input type="text" name="school_address" id="school_address" class="form-control" value="{{ old('school_address') }}" placeholder="e.g., Lagos, Nigeria">
                </div>
                <div class="col-md-12">
                    <label for="school_logo" class="form-label">School Logo (optional)</label>
                    <input type="file" name="school_logo" id="school_logo" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Max 2MB. Accepted formats: JPG, PNG, GIF</small>
                </div>
            </div>

            <div class="mb-3 d-flex gap-2">
                <button type="submit" name="all" value="1" class="btn btn-secondary">
                    <i class="bi bi-download"></i> Download All (Filtered)
                </button>
                <button type="submit" class="btn btn-primary" id="download-selected">
                    <i class="bi bi-download"></i> Download Selected
                </button>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:40px;"><input type="checkbox" id="select-all"></th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>JAMB Attempts</th>
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
                        <tr><td colspan="4" class="text-center" style="padding: 2rem;">No students found matching your filters.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $students->withQueryString()->links() }}
            </div>
        </form>
        @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i> No students with completed JAMB exams found. Try adjusting your date or center filter.
        </div>
        @endif
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
