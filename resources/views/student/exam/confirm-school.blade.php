<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Exam - School CBT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #06b6d4;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            margin-bottom: 1.5rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .header h1 {
            color: #1f2937;
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
        }

        .header p {
            color: #6b7280;
            margin: 0;
            font-size: 1.1rem;
        }

        .info-section {
            margin-bottom: 2rem;
        }

        .info-title {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .student-info {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(6, 182, 212, 0.08) 100%);
            border-left: 4px solid var(--primary);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .student-info h3 {
            margin: 0 0 1rem 0;
            color: #1f2937;
            font-size: 1.3rem;
        }

        .student-detail {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .student-detail:last-child {
            border-bottom: none;
        }

        .student-detail .label {
            color: #6b7280;
            font-weight: 500;
        }

        .student-detail .value {
            color: #1f2937;
            font-weight: 700;
        }

        .subject-card {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
            border: 2px solid #e0e7ff;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .subject-card .subject-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .subject-card h4 {
            color: #1f2937;
            margin: 0 0 0.5rem 0;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .subject-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .subject-detail-item {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .subject-detail-item .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .subject-detail-item .value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .token-section {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
        }

        .token-section h3 {
            color: #92400e;
            margin: 0 0 1rem 0;
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1f2937;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
        }

        .token-status {
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-weight: 600;
            display: none;
        }

        .token-status.success {
            background: #d1fae5;
            color: #065f46;
            display: block;
        }

        .token-status.error {
            background: #fee2e2;
            color: #991b1b;
            display: block;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #4b5563;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .actions {
            display: flex;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
            margin-top: 2rem;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .warning-box {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .warning-box p {
            margin: 0;
            color: #991b1b;
            font-weight: 500;
        }

        /* Mobile Responsive (max-width: 768px) */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }

            .card {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .header h1 {
                font-size: 1.4rem;
            }

            .header p {
                font-size: 0.95rem;
            }

            .student-info {
                padding: 1rem;
            }

            .student-detail {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.35rem;
            }

            .subject-card {
                padding: 1rem;
            }

            .subject-card h4 {
                font-size: 1.1rem;
            }

            .subject-details {
                grid-template-columns: 1fr;
            }

            .token-input {
                font-size: 1.1rem;
                padding: 0.75rem 1rem;
            }

            .btn {
                width: 100%;
            }

            .actions {
                flex-direction: column;
            }
        }

        /* Small Mobile (max-width: 480px) */
        @media (max-width: 480px) {
            .card {
                padding: 1rem;
                border-radius: 14px;
            }

            .header h1 {
                font-size: 1.2rem;
            }

            .info-value {
                font-size: 1rem;
                padding: 0.75rem;
            }

            .token-input {
                font-size: 1rem;
                letter-spacing: 1px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1><i class="bi bi-clipboard-check-fill"></i> Confirm Exam Details</h1>
                <p>Please review your information and validate your exam token</p>
            </div>

            <!-- Student Information -->
            <div class="student-info">
                <h3><i class="bi bi-person-badge"></i> Student Information</h3>
                <div class="student-detail">
                    <span class="label">Name:</span>
                    <span class="value">{{ auth()->user()->name }}</span>
                </div>
                <div class="student-detail">
                    <span class="label">Student ID:</span>
                    <span class="value">{{ auth()->user()->student_id ?? 'N/A' }}</span>
                </div>
                <div class="student-detail">
                    <span class="label">Email:</span>
                    <span class="value">{{ auth()->user()->email }}</span>
                </div>
            </div>

            <!-- Exam Details -->
            <div class="info-section">
                <div class="info-title">Exam Mode</div>
                <div class="info-value">
                    <i class="bi bi-journal-text" style="color: #3b82f6;"></i> School Mock Examination
                </div>
            </div>

            <!-- Subject Card -->
            <div class="subject-card">
                <div class="subject-icon"><i class="bi bi-book-fill"></i></div>
                <h4>{{ $subject->name }}</h4>
                <div class="subject-details">
                    <div class="subject-detail-item">
                        <div class="label">Questions</div>
                        <div class="value">{{ $questionCount }}</div>
                    </div>
                    <div class="subject-detail-item">
                        <div class="label">Time Allowed</div>
                        <div class="value">{{ $duration }} min</div>
                    </div>
                </div>
            </div>

            <!-- Token Validation -->
            <form method="POST" action="{{ route('exam.confirm.school') }}" id="confirmForm">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $subjectId }}">

                <div class="token-section">
                    <h3><i class="bi bi-ticket-perforated-fill"></i> Exam Token Required</h3>
                    <p style="color: #92400e; margin-bottom: 1.5rem;">Enter your exam access token to proceed. Contact your administrator if you don't have one.</p>

                    <div class="form-group">
                        <label for="token_code" class="form-label">Token Code *</label>
                        <input type="text" 
                               id="token_code" 
                               name="token_code" 
                               class="form-control token-input @error('token_code') is-invalid @enderror" 
                               placeholder="ABC-DEF-GHI"
                               value="{{ old('token_code') }}"
                               required
                               autocomplete="off">
                        @error('token_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="tokenStatus" class="token-status"></div>
                    </div>

                    <button type="button" id="validateBtn" class="btn btn-secondary" onclick="validateToken()">
                        <i class="bi bi-search"></i> Validate Token
                    </button>
                </div>

                <div class="warning-box">
                    <p><i class="bi bi-exclamation-triangle-fill"></i> <strong>Important:</strong> Once you start the exam, the timer begins immediately and cannot be paused.</p>
                </div>

                <div class="actions">
                    <button type="submit" id="startBtn" class="btn btn-primary" disabled>
                        <i class="bi bi-play-circle-fill"></i> Start Exam
                    </button>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let tokenValid = false;

        function validateToken() {
            const input = document.getElementById('token_code');
            const statusDiv = document.getElementById('tokenStatus');
            const validateBtn = document.getElementById('validateBtn');
            const startBtn = document.getElementById('startBtn');
            const code = input.value.trim().toUpperCase();

            if (!code) {
                showStatus('Please enter a token code', 'error');
                return;
            }

            // Show loading
            validateBtn.disabled = true;
            validateBtn.innerHTML = '<span class="spinner"></span> Validating...';

            fetch('{{ route("exam.validate.token") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    tokenValid = true;
                    startBtn.disabled = false;
                    showStatus(`✓ Token verified! ${data.remaining_uses} use(s) remaining.`, 'success');
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    tokenValid = false;
                    startBtn.disabled = true;
                    showStatus('✗ ' + data.message, 'error');
                    input.classList.add('is-invalid');
                }
            })
            .catch(error => {
                tokenValid = false;
                startBtn.disabled = true;
                showStatus('✗ Error validating token. Please try again.', 'error');
            })
            .finally(() => {
                validateBtn.disabled = false;
                validateBtn.innerHTML = '<i class="bi bi-search"></i> Validate Token';
            });
        }

        function showStatus(message, type) {
            const statusDiv = document.getElementById('tokenStatus');
            statusDiv.textContent = message;
            statusDiv.className = 'token-status ' + type;
        }

        // Validate on Enter key
        document.getElementById('token_code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                validateToken();
            }
        });

        // Reset validation when token changes
        document.getElementById('token_code').addEventListener('input', function() {
            tokenValid = false;
            document.getElementById('startBtn').disabled = true;
            document.getElementById('tokenStatus').className = 'token-status';
        });
    </script>

    <!-- Footer with Copyright -->
    <footer style="background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 1rem 2rem; text-align: center; margin-top: 2rem;">
        <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
            <strong style="color: #1f2937;">CBT Platform</strong> © 2026 El-Bethel Digital Learning Systems.
        </p>
    </footer>
</body>
</html>
