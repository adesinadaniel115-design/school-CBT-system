<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - School CBT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #06b6d4;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 30px rgba(0, 0, 0, 0.1);
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width 0.25s ease;
        }

        body.sidebar-collapsed .sidebar {
            width: 84px;
        }

        .sidebar-brand {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .sidebar-brand h4 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
            font-size: 1.5rem;
        }

        .sidebar-brand p {
            color: #6b7280;
            margin: 0.5rem 0 0;
            font-size: 0.875rem;
        }

        .sidebar-menu {
            padding: 0 1rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            text-decoration: none;
            color: #4b5563;
            transition: all 0.2s;
            font-weight: 500;
            gap: 0.5rem;
        }

        .menu-item:hover,
        .menu-item.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .menu-item.logout {
            background: #fee2e2;
            color: #b91c1c;
        }

        .menu-item.logout:hover {
            background: #fecaca;
            color: #991b1b;
        }

        .menu-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .menu-icon i {
            font-size: 1.2rem;
        }

        .menu-label {
            white-space: nowrap;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        body.sidebar-collapsed .menu-label,
        body.sidebar-collapsed .sidebar-brand span,
        body.sidebar-collapsed .sidebar-brand p {
            opacity: 0;
            transform: translateX(-6px);
            width: 0;
            overflow: hidden;
            pointer-events: none;
        }

        body.sidebar-collapsed .menu-item {
            justify-content: center;
        }

        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
            transition: margin-left 0.25s ease;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 84px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .sidebar-toggle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
        }

        .sidebar-toggle:hover {
            transform: translateY(-1px);
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.625rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .profile-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.85);
            color: #1f2937;
            text-decoration: none;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }

        .profile-chip:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #eef2ff;
            color: #3730a3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            overflow: hidden;
            flex-shrink: 0;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .invalid-feedback {
            display: block;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: #eef2ff;
            color: #3730a3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #111827;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            color: #111827;
            background: white;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .text-muted {
            color: #6b7280 !important;
            font-size: 0.875rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #4b5563;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h4><i class="bi bi-mortarboard-fill"></i> <span>School CBT</span></h4>
                <p>Computer Based Testing Platform</p>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('student.dashboard') }}" class="menu-item">
                    <span class="menu-icon"><i class="bi bi-house-door-fill"></i></span>
                    <span class="menu-label">Dashboard</span>
                </a>
                <a href="{{ route('student.history') }}" class="menu-item">
                    <span class="menu-icon"><i class="bi bi-clock-history"></i></span>
                    <span class="menu-label">Exam History</span>
                </a>
                <a href="{{ route('student.profile.edit') }}" class="menu-item active">
                    <span class="menu-icon"><i class="bi bi-person-circle"></i></span>
                    <span class="menu-label">Profile</span>
                </a>
                <div style="margin-top: 2rem; padding: 0 1rem;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="menu-item logout w-100 border-0">
                            <span class="menu-icon"><i class="bi bi-box-arrow-right"></i></span>
                            <span class="menu-label">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="main-content">
            <div class="top-actions">
                <button type="button" class="sidebar-toggle" id="studentSidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <a href="{{ route('student.profile.edit') }}" class="profile-chip">
                    <span class="profile-avatar">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile photo">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </span>
                    <span>My Profile</span>
                </a>
            </div>

            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Student Information Card -->
            <div class="profile-card" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(6, 182, 212, 0.05) 100%); border: 1px solid rgba(79, 70, 229, 0.1); margin-bottom: 1.5rem;">
                <h4 style="margin: 0 0 1rem 0; color: #1f2937; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-person-badge-fill" style="color: #4f46e5;"></i> Student Information
                </h4>
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div style="width: 70px; height: 70px; border-radius: 12px; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.8rem; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0; color: #111827; font-size: 1.4rem; font-weight: 700;">{{ $user->name }}</h3>
                        <p style="margin: 0.5rem 0 0; color: #374151; font-size: 1rem; font-weight: 500;">{{ $user->email }}</p>
                        @if($user->student_id)
                            <div style="margin-top: 0.75rem;">
                                <span style="display: inline-block; background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%); color: white; padding: 0.4rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.95rem; box-shadow: 0 2px 8px rgba(3, 105, 161, 0.3);">
                                    Student ID: {{ $user->student_id }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <h3 style="margin: 0 0 1.5rem 0; color: #111827; display: flex; align-items: center; gap: 0.5rem; font-size: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                    <i class="bi bi-person-circle" style="color: var(--primary);"></i> Profile Settings
                </h3>
                <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-5">
                            <h5 style="margin-bottom: 1rem; color: #111827; font-weight: 700;">
                                <i class="bi bi-image" style="color: var(--primary);"></i> Profile Photo
                            </h5>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    @if($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile photo">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/png,image/jpeg">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">PNG or JPG, max 2MB.</small>
                                    @if($user->profile_photo_path)
                                        <div class="mt-2">
                                            <button type="submit" name="remove_photo" value="1" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Remove Photo
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h5 style="margin-bottom: 1rem; color: #111827; font-weight: 700;">
                                <i class="bi bi-lock" style="color: var(--primary);"></i> Change Password
                            </h5>
                            <div class="form-group">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="New password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #e5e7eb;">
                        <button type="submit" class="btn btn-primary" style="font-size: 1rem;">
                            <i class="bi bi-check-circle-fill"></i> Save Changes
                        </button>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary" style="font-size: 1rem;">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const storageKey = 'studentSidebarCollapsed';
            const toggle = document.getElementById('studentSidebarToggle');

            if (localStorage.getItem(storageKey) === 'true') {
                document.body.classList.add('sidebar-collapsed');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem(storageKey, document.body.classList.contains('sidebar-collapsed'));
                });
            }
        })();
    </script>
    <script>
        (function () {
            const sidebar = document.querySelector('.sidebar');
            if (!sidebar) return;

            const key = 'studentSidebarScrollTop';
            const saved = sessionStorage.getItem(key);
            if (saved) {
                sidebar.scrollTop = parseInt(saved, 10) || 0;
            }

            sidebar.addEventListener('scroll', function () {
                sessionStorage.setItem(key, String(sidebar.scrollTop));
            });
        })();
    </script>

    <!-- Footer with Copyright -->
    <footer style="background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 1rem 2rem; text-align: center; margin-top: 2rem;">
        <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
            <strong style="color: #1f2937;">CBT Platform</strong> © 2026 El-Bethel Digital Learning Systems.
        </p>
    </footer>
</body>
</html>
