<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - School CBT</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 2.8rem;
            color: #9ca3af;
            font-size: 1.25rem;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-primary {
            padding: 0.875rem;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            padding: 0.875rem;
            background: #f3f4f6;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .success-alert {
            background: #d1fae5;
            border: 2px solid #10b981;
            color: #047857;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .info-text {
            color: #6b7280;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 1rem;
        }

        .divider-text {
            text-align: center;
            color: #9ca3af;
            margin: 1.5rem 0;
            font-size: 0.9rem;
        }

        .token-display {
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
            font-family: monospace;
            font-size: 0.85rem;
            word-break: break-all;
            color: #374151;
        }

        .copy-btn {
            background: white;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
            width: 100%;
        }

        .copy-btn:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="bi bi-key-fill"></i>
                </div>
                <h1 class="auth-title">Forgot Password?</h1>
                <p class="auth-subtitle">Enter your email to reset your password</p>
            </div>

            @if($errors->any())
                <div style="background: #fee2e2; border: 2px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <p style="margin: 0.5rem 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('status'))
                <div class="success-alert">
                    <strong>✓ Email Found!</strong><br>
                    {{ session('status') }}

                    @php
                        $email = session('reset_token_' . request('email')) ? request('email') : null;
                        $token = $email ? session('reset_token_' . $email) : null;
                        $link = $email ? session('reset_token_link_' . $email) : null;
                    @endphp

                    @if($link)
                        <div class="token-display">
                            <strong>Your Reset Link:</strong><br>
                            <a href="{{ $link }}" target="_blank" style="color: #0284c7; word-break: break-all;">
                                {{ $link }}
                            </a>
                        </div>
                        <button class="copy-btn" onclick="copyToClipboard('{{ $link }}')">
                            <i class="bi bi-clipboard"></i> Copy Link
                        </button>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <i class="bi bi-envelope form-icon"></i>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        class="form-control"
                        placeholder="example@school.com"
                        value="{{ old('email') }}"
                        required
                    >
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-arrow-right"></i> Send Reset Link
                    </button>
                    <a href="{{ route('login') }}" class="btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </div>
            </form>

            <p class="info-text">
                <i class="bi bi-info-circle"></i> Reset links expire after 24 hours
            </p>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copied to clipboard!');
            }).catch(() => {
                alert('Failed to copy. Please copy manually.');
            });
        }
    </script>
</body>
</html>
