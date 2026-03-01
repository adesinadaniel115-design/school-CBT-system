@extends('layouts.admin')

@section('title', 'Generate Exam Tokens')
@section('page-title', 'Generate Exam Tokens')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-ticket-perforated-fill"></i> Token Configuration
                </h3>
                <a href="{{ route('admin.tokens.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <form method="POST" action="{{ route('admin.tokens.store') }}">
                @csrf

                <div class="form-group">
                    <label for="center_id" class="form-label">Center (optional)</label>
                    <select id="center_id" name="center_id" class="form-control @error('center_id') is-invalid @enderror">
                        <option value="">-- select center --</option>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>
                                {{ $center->name }}@if($center->location) ({{ $center->location }})@endif
                            </option>
                        @endforeach
                    </select>
                    @error('center_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity" class="form-label">Number of Tokens to Generate *</label>
                    <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                           value="{{ old('quantity', 1) }}" min="1" max="100" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small style="color: #6b7280;">Generate between 1 and 100 tokens at once (updated by center below)</small>
                </div>

                <div class="form-group">
                    <label for="max_uses" class="form-label">Maximum Uses Per Token *</label>
                    <input type="number" id="max_uses" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror" 
                           value="{{ old('max_uses', 1) }}" min="1" max="1000" required>
                    @error('max_uses')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small style="color: #6b7280;">How many times can each token be used? (1 = single use)</small>
                </div>

                <div class="form-group">
                    <label for="expires_at" class="form-label">Expiration Date (Optional)</label>
                    <input type="date" id="expires_at" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" 
                           value="{{ old('expires_at') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('expires_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small style="color: #6b7280;">Leave blank for no expiration</small>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" 
                              rows="3" placeholder="e.g., June 2026 Mock Exam">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small style="color: #6b7280;">Add notes to help identify these tokens</small>
                </div>

                <div style="display: flex; gap: 1rem; padding-top: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Generate Tokens
                    </button>
                    <a href="{{ route('admin.tokens.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card" style="background: #eff6ff; border-left: 4px solid #3b82f6;">
            <h5 style="color: #1e40af; margin-bottom: 1rem;">
                <i class="bi bi-info-circle-fill"></i> About Exam Tokens
            </h5>
            <ul style="color: #1e40af; padding-left: 1.25rem; margin: 0;">
                <li>Tokens control who can take exams</li>
                <li>Each token has a unique code (e.g., ABC-DEF-GHI)</li>
                <li>Set usage limits to control access</li>
                <li>Track which students used each token</li>
                <li>Deactivate or delete tokens anytime</li>
            </ul>
        </div>

        <div class="card" style="background: #fef3c7; border-left: 4px solid #f59e0b; margin-top: 1.5rem;">
            <h5 style="color: #92400e; margin-bottom: 1rem;">
                <i class="bi bi-lightbulb-fill"></i> Usage Examples
            </h5>
            <div style="color: #92400e; font-size: 0.9rem;">
                <div style="margin-bottom: 1rem;">
                    <strong>Single Student:</strong><br>
                    Quantity: 1, Max Uses: 1
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Class of 30:</strong><br>
                    Quantity: 30, Max Uses: 1
                </div>
                <div>
                    <strong>Reusable Token:</strong><br>
                    Quantity: 1, Max Uses: 100
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .card-header .btn {
            width: 100%;
        }

        .col-lg-8,
        .col-lg-4 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        form > div[style*="display: flex"] {
            flex-direction: column;
        }

        form > div[style*="display: flex"] .btn {
            width: 100%;
        }

        .card {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 480px) {
        .card {
            padding: 1rem !important;
        }

        .form-control,
        .form-label {
            font-size: 0.95rem;
        }
    }
</style>

@push('scripts')
<script>
    document.getElementById('center_id')?.addEventListener('change', function(e) {
        const centerId = e.target.value;
        if (!centerId) {
            return;
        }
        fetch('/admin/centers/' + centerId + '/students')
            .then(r => r.json())
            .then(data => {
                const count = (data.students || []).length;
                if (count > 0) {
                    document.getElementById('quantity').value = count;
                }
            });
    });
</script>
@endpush
@endsection
