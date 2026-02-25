<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - School CBT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
        }

        .auth-card {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .auth-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1.5rem;
        }

        .auth-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 2.8rem;
            color: #9ca3af;
            font-size: 1.25rem;
        }

        .password-strength {
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            flex: 1;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            background: #10b981;
            width: 0%;
            transition: width 0.3s;
        }

        .btn-primary {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary {
            width: 100%;
            padding: 0.875rem;
            background: #f3f4f6;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .info-text {
            color: #6b7280;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 1rem;
        }

        .password-requirements {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 8px;
            padding: 0.75rem;
            margin: 1rem 0;
            font-size: 0.85rem;
            color: #166534;
        }

        .password-requirements li {
            margin: 0.25rem 0;
        }

        .password-requirements.valid {
            background: #d1fae5;
            border-color: #a7f3d0;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="bi bi-shield-check-fill"></i>
                </div>
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle">Create a new password for your account</p>
            </div>

            @if($errors->any())
                <div style="background: #fee2e2; border: 2px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <p style="margin: 0.5rem 0;">✗ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <i class="bi bi-envelope form-icon"></i>
                    <input 
                        type="email" 
                        id="email"
                        class="form-control"
                        value="{{ $email }}"
                        disabled
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <i class="bi bi-lock form-icon"></i>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        class="form-control"
                        placeholder="Enter new password"
                        required
                        minlength="6"
                        onchange="updatePasswordStrength()"
                        oninput="updatePasswordStrength()"
                    >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span id="strengthText" style="color: #6b7280;">Weak</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <i class="bi bi-lock-fill form-icon"></i>
                    <input 
                        type="password" 
                        id="password_confirmation"
                        name="password_confirmation" 
                        class="form-control"
                        placeholder="Confirm new password"
                        required
                    >
                    @error('password_confirmation')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="password-requirements" id="requirements">
                    <strong>Password Requirements:</strong>
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        <li id="req-length">✓ At least 6 characters</li>
                    </ul>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-circle"></i> Reset Password
                </button>

                <a href="{{ route('login') }}" class="btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Login
                </a>
            </form>

            <p class="info-text">
                <i class="bi bi-lock"></i> Your password is securely encrypted
            </p>
        </div>
    </div>

    <script>
        function updatePasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            const requirements = document.getElementById('requirements');

            let strength = 0;
            let text = 'Weak';
            let color = '#ef4444';

            if (password.length >= 6) strength += 25;
            if (password.length >= 10) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9!@#$%^&*]/.test(password)) strength += 25;

            if (strength < 50) {
                text = 'Weak';
                color = '#ef4444';
            } else if (strength < 75) {
                text = 'Fair';
                color = '#f59e0b';
            } else if (strength < 100) {
                text = 'Strong';
                color = '#10b981';
            } else {
                text = 'Very Strong';
                color = '#059669';
            }

            strengthFill.style.width = strength + '%';
            strengthFill.style.background = color;
            strengthText.textContent = text;
            strengthText.style.color = color;

            // Update requirements
            if (password.length >= 6) {
                requirements.classList.add('valid');
            } else {
                requirements.classList.remove('valid');
            }
        }

        document.addEventListener('DOMContentLoaded', updatePasswordStrength);
    </script>
</body>
</html>
