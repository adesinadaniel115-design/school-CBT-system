<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exam History - School CBT</title>
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
            transition: width 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
        }

        body.sidebar-collapsed .sidebar {
            width: 84px;
        }

        body.sidebar-collapsed .sidebar-brand,
        body.sidebar-collapsed .sidebar-menu {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .sidebar-brand span,
        .sidebar-brand p,
        .menu-label {
            transition: opacity 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
        }

        body.sidebar-collapsed .sidebar-brand span,
        body.sidebar-collapsed .sidebar-brand p {
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

        body.sidebar-collapsed .menu-item {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .menu-item:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            transform: translateX(5px);
        }

        .menu-item.logout {
            background: #fee2e2;
            color: #b91c1c;
        }

        .menu-item.logout:hover {
            background: #fecaca;
            color: #991b1b;
            transform: none;
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

        body.sidebar-collapsed .menu-label {
            opacity: 0;
            transform: translateX(-6px);
            width: 0;
            overflow: hidden;
            pointer-events: none;
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

        .btn-danger-lite {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            border-radius: 12px;
            border: none;
            background: #fee2e2;
            color: #b91c1c;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-danger-lite:hover {
            background: #fecaca;
            transform: translateY(-1px);
        }

        .sidebar-toggle i {
            transition: transform 0.25s ease;
        }

        body.sidebar-collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        .sidebar-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        /* export controls at top of history list */
        .export-controls {
            background: #ffffffcc;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            align-items: center;
        }

        .export-controls input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .page-header p {
            color: #6b7280;
            margin: 0.5rem 0 0;
        }

        .exam-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .exam-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .exam-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .exam-icon.school {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .exam-icon.jamb {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .exam-content {
            flex: 1;
        }

        .exam-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .exam-badge.school {
            background: #dbeafe;
            color: #1e40af;
        }

        .exam-badge.jamb {
            background: #fef3c7;
            color: #92400e;
        }

        .exam-title {
            font-weight: 700;
            color: #1f2937;
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
        }

        .exam-meta {
            display: flex;
            gap: 1.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .exam-meta i {
            color: var(--primary);
        }

        .score-badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.125rem;
            text-align: center;
            min-width: 100px;
        }

        .score-badge.excellent {
            background: #d1fae5;
            color: #065f46;
        }

        .score-badge.good {
            background: #dbeafe;
            color: #1e40af;
        }

        .score-badge.average {
            background: #fef3c7;
            color: #92400e;
        }

        .score-badge.poor {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-view {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .logout-btn {
            position: absolute;
            top: 2rem;
            right: 2rem;
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

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: white;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1050;
                width: 280px;
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.15);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
            
            /* Sidebar backdrop overlay */
            .sidebar::after {
                content: '';
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.25s ease;
                z-index: -1;
            }
            
            .sidebar.show::after {
                opacity: 1;
                pointer-events: all;
            }
            
            .sidebar-toggle {
                display: flex !important;
            }
        }

        /* Mobile Responsive (max-width: 768px) */
        @media (max-width: 768px) {
            body {
                padding: 0;
            }

            .main-content {
                padding: 1rem;
            }

            .top-actions {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
            }
            
            .sidebar-toggle {
                flex-shrink: 0;
            }

            .profile-chip {
                flex: 1;
                justify-content: center;
            }

            .history-card {
                padding: 1.5rem !important;
            }

            .exam-grid {
                grid-template-columns: 1fr !important;
            }

            .btn {
                font-size: 0.95rem;
                padding: 0.6rem 1rem;
            }
            
            .session-badge {
                font-size: 0.75rem;
            }
            
            .exam-badge {
                display: inline-block;
                font-size: 0.75rem;
            }
        }

        /* Small Mobile (max-width: 480px) */
        @media (max-width: 480px) {
            .main-content {
                padding: 0.75rem;
            }

            .history-card {
                padding: 1rem !important;
            }

            .exam-title {
                font-size: 1rem !important;
            }

            .exam-meta {
                font-size: 0.8rem !important;
            }

            .profile-avatar {
                width: 32px;
                height: 32px;
            }
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
                <a href="{{ route('student.history') }}" class="menu-item active">
                    <span class="menu-icon"><i class="bi bi-clock-history"></i></span>
                    <span class="menu-label">Exam History</span>
                </a>
                <a href="{{ route('student.profile.edit') }}" class="menu-item">
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
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile photo">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </span>
                    <span>My Profile</span>
                </a>
                <form method="POST" action="{{ route('student.history.clear') }}" onsubmit="return confirm('Clear your exam history? This cannot be undone.');">
                    @csrf
                    <button type="submit" class="btn-danger-lite">
                        <i class="bi bi-trash3-fill"></i> Clear History
                    </button>
                </form>
            </div>

            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="page-header">
                <h1><i class="bi bi-clock-history"></i> Exam History</h1>
                <p>View all your completed exams and performance records</p>
            </div>

            @if($sessions->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>No Exam History Yet</h3>
                    <p>You haven't completed any exams. Start your first exam to see your results here!</p>
                    <a href="{{ route('student.dashboard') }}" class="btn-view">
                        <i class="bi bi-plus-circle"></i> Start an Exam
                    </a>
                </div>
            @else
                <form method="POST" action="{{ route('student.history.generate') }}" id="exportForm">
                    @csrf
                    <div class="export-controls mb-3 d-flex align-items-center gap-2">
                        <input type="checkbox" id="selectAll" /> <label for="selectAll" class="mb-0">Select all</label>
                        <button type="submit" class="btn btn-primary btn-sm">Download Selected</button>
                        <button type="submit" name="all" value="1" class="btn btn-secondary btn-sm">Download All</button>
                    </div>
                
                    @foreach($sessions as $session)
                        <div class="exam-card">
                        <input type="checkbox" name="session_ids[]" value="{{ $session->id }}" class="session-checkbox" />
                        <div class="exam-icon {{ $session->exam_mode }}">
                            <i class="bi {{ $session->exam_mode === 'jamb' ? 'bi-lightning-charge-fill' : 'bi-journal-text' }}"></i>
                        </div>
                        <div class="exam-content">
                            <span class="exam-badge {{ $session->exam_mode }}">{{ strtoupper($session->exam_mode) }}</span>
                            <div class="exam-title">
                                {{ $session->exam_mode === 'jamb' ? 'JAMB Mock Exam' : $session->subject->name }}
                            </div>
                            <div class="exam-meta">
                                <span><i class="bi bi-calendar3"></i> {{ $session->completed_at->format('M d, Y') }}</span>
                                <span><i class="bi bi-clock"></i> {{ $session->completed_at->format('H:i') }}</span>
                                <span><i class="bi bi-question-circle"></i> {{ $session->total_questions }} questions</span>
                            </div>
                        </div>
                        @php
                            if ($session->exam_mode === 'jamb') {
                                $percentage = ($session->score / 400) * 100;
                            } else {
                                $percentage = ($session->score / $session->total_questions) * 100;
                            }
                            
                            if ($percentage >= 80) {
                                $scoreClass = 'excellent';
                            } elseif ($percentage >= 60) {
                                $scoreClass = 'good';
                            } elseif ($percentage >= 40) {
                                $scoreClass = 'average';
                            } else {
                                $scoreClass = 'poor';
                            }
                        @endphp
                        <div class="score-badge {{ $scoreClass }}">
                            @if($session->exam_mode === 'jamb')
                                {{ number_format($session->score, 0) }}/400
                            @else
                                {{ $session->score }}/{{ $session->total_questions }}
                            @endif
                        </div>
                        <a href="{{ route('exam.result', $session) }}" class="btn-view">
                            View Details <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $sessions->links('pagination::bootstrap-5') }}
                </div>
                </form>
            @endif
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const storageKey = 'studentSidebarCollapsed';
            const toggle = document.getElementById('studentSidebarToggle');
            const sidebar = document.querySelector('.sidebar');

            if (localStorage.getItem(storageKey) === 'true') {
                document.body.classList.add('sidebar-collapsed');
            }

            if (toggle) {
                toggle.addEventListener('click', function (e) {
                    // On mobile (< 991px), show/hide sidebar with `.show` class
                    if (window.innerWidth < 991) {
                        sidebar?.classList.toggle('show');
                        e.stopPropagation();
                    } else {
                        // On desktop, use the collapse toggle
                        document.body.classList.toggle('sidebar-collapsed');
                        localStorage.setItem(storageKey, document.body.classList.contains('sidebar-collapsed'));
                    }
                });
            }
            
            // Close sidebar when clicking outside on mobile
            if (sidebar) {
                document.addEventListener('click', function (e) {
                    if (window.innerWidth < 991) {
                        if (!sidebar.contains(e.target) && e.target !== toggle && !toggle?.contains(e.target)) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
            }
            
            // Close sidebar on resize to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 991) {
                    sidebar?.classList.remove('show');
                }
            });
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
    <script>
        // select all / deselect all checkboxes for export
        (function () {
            const selectAll = document.getElementById('selectAll');
            if (!selectAll) return;
            const checkboxes = document.querySelectorAll('.session-checkbox');

            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
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
