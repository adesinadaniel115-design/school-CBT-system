<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - School CBT</title>
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
            max-width: 500px;
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
            margin-bottom: 1.25rem;
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

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.5rem;
            color: #9ca3af;
            font-size: 1.125rem;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            color: #6b7280;
            font-size: 0.875rem;
            position: relative;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: white;
            color: #10b981;
            border: 2px solid #10b981;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.125rem;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: #10b981;
            color: white;
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div class="auth-title">Create Account</div>
                <div class="auth-subtitle">Join us to start your learning journey</div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <i class="bi bi-person input-icon"></i>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter your full name" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <i class="bi bi-envelope input-icon"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="center_id" class="form-label">Select Center</label>
                    <i class="bi bi-building input-icon" style="left:1rem;"></i>
                    <select id="center_id" name="center_id" class="form-control">
                        <option value="">-- No center / Not listed --</option>
                        @forelse($centers as $center)
                            <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                        @empty
                            <option value="" disabled>(no centers defined yet)</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <i class="bi bi-lock input-icon"></i>
                    <input id="password" type="password" name="password" class="form-control" placeholder="Create a password" autocomplete="new-password" value="" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <i class="bi bi-shield-lock input-icon"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" autocomplete="new-password" value="" required>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Create Account
                </button>
            </form>

            <div class="divider">
                <span>Already have an account?</span>
            </div>

            <a href="{{ route('login') }}" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Sign In
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
