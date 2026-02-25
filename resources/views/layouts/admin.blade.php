<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - School CBT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f3f4f6;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: width 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease, left 0.3s ease;
        }

        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .sidebar-brand {
            padding: 1.5rem 0.75rem;
            text-align: center;
        }

        .sidebar-brand span,
        .sidebar-brand p,
        .menu-section-title,
        .menu-label {
            transition: opacity 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
        }

        body.sidebar-collapsed .sidebar-brand span,
        body.sidebar-collapsed .sidebar-brand p,
        body.sidebar-collapsed .menu-section-title,
        body.sidebar-collapsed .menu-label {
            opacity: 0;
            transform: translateX(-8px);
            pointer-events: none;
        }

        body.sidebar-collapsed .menu-item {
            justify-content: center;
        }

        body.sidebar-collapsed .menu-item:hover {
            transform: none;
        }

        body.sidebar-collapsed .menu-item i {
            margin-right: 0;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h4 {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
            margin: 0;
        }

        .sidebar-menu {
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .menu-section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 1.5rem 0 0.75rem 1rem;
        }

        .menu-section-title:first-child {
            margin-top: 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.2s;
            font-weight: 500;
            margin-bottom: 0.25rem;
            min-height: 44px;
            gap: 0.5rem;
        }

        body.sidebar-collapsed .menu-item {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
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
            transition: opacity 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
        }

        body.sidebar-collapsed .sidebar-menu {
            padding: 1rem 0.5rem;
            gap: 0.5rem;
        }

        body.sidebar-collapsed .menu-item {
            justify-content: center;
            padding: 0.75rem;
            margin-bottom: 0;
            border-radius: 12px;
        }

        body.sidebar-collapsed .menu-label {
            opacity: 0;
            transform: translateX(-6px);
            width: 0;
            overflow: hidden;
            pointer-events: none;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.25s ease;
        }

        body.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-left h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-toggle {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .sidebar-toggle i {
            transition: transform 0.25s ease;
        }

        body.sidebar-collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        .sidebar-toggle:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: #f3f4f6;
            border-radius: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            overflow: hidden;
        }

        .user-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.875rem;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 2rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: start;
            gap: 0.75rem;
        }

        .alert i {
            font-size: 1.25rem;
            margin-top: 0.125rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
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

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Buttons */
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }

        .btn-light {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-light:hover {
            background: #e5e7eb;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f9fafb;
        }

        th {
            padding: 0.875rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }

        tr:hover {
            background: #f9fafb;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-top: 0.125rem;
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
        }

        .form-check {
            display: flex;
            align-items: start;
            gap: 0.75rem;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding:1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .stat-icon.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .stat-icon.yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-icon.red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .stat-content h3 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 0.25rem 0;
        }

        .stat-content p {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                left: -100%;
            }

            .sidebar.mobile-open {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .header h1 {
                font-size: 1.125rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h4>
                    <i class="bi bi-shield-fill-check"></i>
                    <span>Admin Panel</span>
                </h4>
                <p>School CBT Management</p>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section-title">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-speedometer2"></i></span>
                    <span class="menu-label">Dashboard</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-graph-up"></i></span>
                    <span class="menu-label">Analytics & Reports</span>
                </a>

                <div class="menu-section-title">User Management</div>
                <a href="{{ route('admin.students.index') }}" class="menu-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-people"></i></span>
                    <span class="menu-label">Students</span>
                </a>

                <div class="menu-section-title">Content Management</div>
                <a href="{{ route('admin.subjects.index') }}" class="menu-item {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-book"></i></span>
                    <span class="menu-label">Subjects</span>
                </a>
                <a href="{{ route('admin.questions.index') }}" class="menu-item {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-question-circle"></i></span>
                    <span class="menu-label">Questions</span>
                </a>

                <div class="menu-section-title">Configuration</div>
                <a href="{{ route('admin.tokens.index') }}" class="menu-item {{ request()->routeIs('admin.tokens.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-ticket-perforated"></i></span>
                    <span class="menu-label">Exam Tokens</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-gear"></i></span>
                    <span class="menu-label">Exam Settings</span>
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="menu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-person-circle"></i></span>
                    <span class="menu-label">My Profile</span>
                </a>

                <div class="menu-section-title">Quick Actions</div>
                <a href="{{ route('student.dashboard') }}" class="menu-item">
                    <span class="menu-icon"><i class="bi bi-eye"></i></span>
                    <span class="menu-label">View as Student</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <button type="button" class="sidebar-toggle" id="adminSidebarToggle" aria-label="Toggle sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    <a href="{{ route('admin.profile.edit') }}" class="user-info" style="text-decoration: none;">
                        <div class="user-avatar">
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile photo" class="user-avatar-img">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="user-name">{{ auth()->user()->name }}</div>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer with Copyright -->
    <footer style="background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 1rem 2rem; text-align: center; margin-left: 250px; transition: margin-left 0.3s ease;">
        <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
            <strong style="color: #1f2937;">CBT Platform</strong> © 2026 El-Bethel Digital Learning Systems.
        </p>
    </footer>

    <style>
        body.sidebar-collapsed footer {
            margin-left: 60px;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 1rem !important;
            }

            .header h1 {
                font-size: 1.05rem !important;
            }

            .header-left {
                gap: 0.5rem !important;
            }

            .sidebar-toggle {
                width: 40px !important;
                height: 40px !important;
                min-width: 40px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            footer {
                margin-left: 0 !important;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const storageKey = 'adminSidebarCollapsed';
            const body = document.body;
            const toggle = document.getElementById('adminSidebarToggle');
            const sidebar = document.querySelector('.sidebar');

            if (localStorage.getItem(storageKey) === 'true') {
                body.classList.add('sidebar-collapsed');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    // On mobile: toggle sidebar visibility
                    if (window.innerWidth <= 768) {
                        if (sidebar) {
                            sidebar.classList.toggle('mobile-open');
                        }
                    } else {
                        // On desktop: collapse/expand sidebar
                        body.classList.toggle('sidebar-collapsed');
                        localStorage.setItem(storageKey, body.classList.contains('sidebar-collapsed'));
                    }
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 && sidebar && sidebar.classList.contains('mobile-open')) {
                    if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                        sidebar.classList.remove('mobile-open');
                    }
                }
            });
        })();
    </script>
    <script>
        (function () {
            const sidebar = document.querySelector('.sidebar');
            if (!sidebar) return;

            const key = 'adminSidebarScrollTop';
            const saved = sessionStorage.getItem(key);
            if (saved) {
                sidebar.scrollTop = parseInt(saved, 10) || 0;
            }

            sidebar.addEventListener('scroll', function () {
                sessionStorage.setItem(key, String(sidebar.scrollTop));
            });
        })();
    </script>
    @yield('scripts')
</body>
</html>
