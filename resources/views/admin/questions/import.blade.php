@extends('layouts.admin')

@section('title', 'Import Questions')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}" class="text-decoration-none">Questions</a></li>
            <li class="breadcrumb-item active">Import CSV</li>
        </ol>
    </nav>
    <h3 class="mb-1">Import Questions from CSV</h3>
    <p class="text-muted small mb-0">Bulk upload questions with automatic subject creation and image support</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> All existing questions will be deleted before import. Exam sessions and answers are preserved and remain valid.
                </div>

                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Required Columns (case-insensitive):</strong>
                    <div class="mt-2">
                        <code>subject</code>, <code>question_text</code>, <code>option_a</code>, <code>option_b</code>, <code>option_c</code>, <code>option_d</code>, <code>correct_option</code>, <code>difficulty_level</code>
                    </div>
                </div>

                <div class="mb-4 p-3 bg-success bg-opacity-10 rounded border border-success border-opacity-25">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i>
                            <strong class="text-success">Get Started with a Template</strong>
                            <p class="text-muted small mb-0 mt-1">Download a pre-formatted CSV file with sample questions to follow</p>
                        </div>
                        <a href="{{ route('admin.questions.import.template') }}" class="btn btn-success ms-3" download>
                            <i class="bi bi-download me-2"></i>Download Template
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.questions.import') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="file" class="form-label fw-semibold">CSV File <span class="text-danger">*</span></label>
                        <input type="file" 
                               class="form-control form-control-lg @error('file') is-invalid @enderror" 
                               id="file" 
                               name="file" 
                               accept=".csv,.txt"
                               required>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">Accepted formats: CSV, TXT</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-cloud-upload me-2"></i>Import Questions
                        </button>
                        <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-2"></i>Back to Questions
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(session('import_summary'))
            <div class="card border-0 border-start border-success border-4 bg-success bg-opacity-10 mt-4">
                <div class="card-body">
                    <h5 class="text-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>Import Summary
                    </h5>
                    @php
                        $summary = session('import_summary');
                    @endphp
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <div class="text-muted small">Deleted Questions</div>
                                <div class="h5 text-danger mb-0">{{ $summary['deleted_count'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <div class="text-muted small">Successfully Imported</div>
                                <div class="h5 text-success mb-0">{{ $summary['inserted_count'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <div class="text-muted small">Skipped (Errors)</div>
                                <div class="h5 text-warning mb-0">{{ $summary['skipped_count'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-info bg-opacity-10 rounded">
                                <div class="text-muted small">Total Processed</div>
                                <div class="h5 text-info mb-0">{{ $summary['inserted_count'] + $summary['skipped_count'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('import_errors'))
            <div class="card border-0 border-start border-danger border-4 bg-danger bg-opacity-10 mt-4">
                <div class="card-body">
                    <h5 class="text-danger mb-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>Import Errors ({{ count(session('import_errors')) }} rows)
                    </h5>
                    <div class="list-group list-group-flush">
                        @foreach(session('import_errors') as $line => $messages)
                            <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                <strong class="text-danger">Row {{ $line }}:</strong> {{ implode('; ', $messages) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body">
                <h5 class="card-title text-primary mb-3">
                    <i class="bi bi-info-circle me-2"></i>File Format Guide
                </h5>
                <div class="small">
                    <p class="mb-3"><strong>Required Columns:</strong></p>
                    <table class="table table-sm mb-3">
                        <tbody>
                            <tr>
                                <td><code>subject</code></td>
                                <td><small class="text-muted">Subject name (auto-create if new)</small></td>
                            </tr>
                            <tr>
                                <td><code>question_text</code></td>
                                <td><small class="text-muted">Question content</small></td>
                            </tr>
                            <tr>
                                <td><code>option_a</code></td>
                                <td><small class="text-muted">Option A text</small></td>
                            </tr>
                            <tr>
                                <td><code>option_b</code></td>
                                <td><small class="text-muted">Option B text</small></td>
                            </tr>
                            <tr>
                                <td><code>option_c</code></td>
                                <td><small class="text-muted">Option C text</small></td>
                            </tr>
                            <tr>
                                <td><code>option_d</code></td>
                                <td><small class="text-muted">Option D text</small></td>
                            </tr>
                            <tr>
                                <td><code>correct_option</code></td>
                                <td><small class="text-muted">A, B, C, or D</small></td>
                            </tr>
                            <tr>
                                <td><code>difficulty_level</code></td>
                                <td><small class="text-muted">easy/medium/hard</small></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="mb-2"><strong>Optional:</strong></p>
                    <ul class="ps-3 mb-0">
                        <li><code>explanation</code> - Answer explanation</li>
                        <li><code>image</code> - Image filename or base64 data</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mt-3">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Sample Row</h6>
                <div class="small font-monospace bg-white p-2 rounded border">
                    <div>subject,Mathematics</div>
                    <div>question_text,What is 2+2?</div>
                    <div>option_a,3</div>
                    <div>option_b,4</div>
                    <div>option_c,5</div>
                    <div>option_d,6</div>
                    <div>correct_option,B</div>
                    <div>difficulty_level,easy</div>
                    <div>explanation,2+2 equals 4</div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3 mb-0">
            <i class="bi bi-info-circle me-2"></i>
            <small><strong>Auto-create Subjects:</strong> If the subject column contains a name not in the database, it will be created automatically.</small>
        </div>
    </div>
</div>
@endsection

