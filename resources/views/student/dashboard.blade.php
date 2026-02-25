<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard - School CBT</title>
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

        /* Sidebar */
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

        /* Main Content */
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

        /* Welcome Card */
        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: #6b7280;
            font-size: 1.125rem;
            margin-bottom: 1.5rem;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-box {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
        }

        .stat-box i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Exam Cards */
        .exam-section {
            margin-bottom: 2rem;
        }

        .exam-section h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .exam-section h3 i {
            margin-right: 0.75rem;
            font-size: 1.75rem;
        }

        .exam-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .exam-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .exam-card-header {
            display: flex;
            justify-content: between;
            align-items: start;
            margin-bottom: 1.5rem;
        }

        .exam-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .exam-badge.school {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .exam-badge.jamb {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .exam-card h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem;
        }

        .exam-info {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
            color: #6b7280;
        }

        .exam-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .exam-info-item i {
            color: var(--primary);
        }

        .form-select, .form-check {
            margin-bottom: 1rem;
        }

        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem;
            font-size: 1rem;
        }

        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .form-check {
            background: #f9fafb;
            padding: 0.875rem;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            transition: all 0.2s;
        }

        .form-check:hover {
            border-color: var(--primary);
            background: #eff6ff;
        }

        .form-check-input:checked ~ .form-check-label {
            color: var(--primary);
            font-weight: 600;
        }

        .btn-exam {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-exam:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(79, 70, 229, 0.4);
        }

        .btn-exam i {
            font-size: 1.25rem;
        }

        /* Active Sessions */
        .session-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .session-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .session-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .session-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .session-badge.ongoing {
            background: #fef3c7;
            color: #92400e;
        }

        .session-title {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .session-info {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .btn-continue {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }

        .session-actions {
            display: grid;
            gap: 0.5rem;
        }

        .btn-terminate {
            background: #ef4444;
        }

        .btn-terminate:hover {
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-continue:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
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
                z-index: 1000;
            }

            .main-content {
                margin-left: 0;
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
                flex-direction: column;
                align-items: stretch;
            }

            .profile-chip {
                width: 100%;
                justify-content: center;
            }

            .welcome-card {
                padding: 1.5rem !important;
            }

            .welcome-title {
                font-size: 1.5rem !important;
            }

            .welcome-subtitle {
                font-size: 0.9rem !important;
            }

            .quick-stats {
                grid-template-columns: 1fr !important;
            }

            .stat-box {
                padding: 1rem !important;
            }

            .exam-section h3 {
                font-size: 1.3rem;
            }

            .exam-card {
                padding: 1.5rem !important;
            }

            .exam-card h4 {
                font-size: 1.2rem;
            }

            .exam-info {
                flex-direction: column;
                gap: 0.5rem;
            }

            .checkbox-group {
                grid-template-columns: 1fr !important;
            }

            .form-select-lg {
                font-size: 1rem;
                padding: 0.5rem 0.75rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.95rem;
            }
        }

        /* Small Mobile (max-width: 480px) */
        @media (max-width: 480px) {
            .welcome-card {
                padding: 1rem !important;
            }

            .welcome-title {
                font-size: 1.2rem !important;
            }

            .exam-card {
                padding: 1rem !important;
                margin-bottom: 1rem;
            }

            .exam-card h4 {
                font-size: 1.1rem;
            }

            .exam-info-item {
                font-size: 0.85rem;
            }

            .btn-exam {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
            }

            .stat-box {
                padding: 0.75rem !important;
                gap: 0.5rem;
            }

            .stat-value {
                font-size: 1.5rem !important;
            }

            .stat-label {
                font-size: 0.75rem !important;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h4><i class="bi bi-mortarboard-fill"></i> <span>School CBT</span></h4>
                <p>Computer Based Testing Platform</p>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('student.dashboard') }}" class="menu-item active">
                    <span class="menu-icon"><i class="bi bi-house-door-fill"></i></span>
                    <span class="menu-label">Dashboard</span>
                </a>
                <a href="{{ route('student.history') }}" class="menu-item">
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

        <!-- Main Content -->
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
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Oops!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Welcome Card -->
            <div class="welcome-card">
                <div class="welcome-content">
                    <div class="welcome-title">
                        👋 Welcome back, {{ auth()->user()->name }}!
                    </div>
                    @if(auth()->user()->student_id)
                        <div style="margin: 1.5rem 0 1.5rem; background: linear-gradient(135deg, rgba(3, 105, 161, 0.08) 0%, rgba(6, 182, 212, 0.08) 100%); padding: 1rem; border-radius: 12px; border-left: 4px solid #0369a1;">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                                <div style="background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 700; font-size: 1.2rem; min-width: 100px; text-align: center;">
                                    {{ auth()->user()->student_id }}
                                </div>
                                <div>
                                    <div style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Your Student ID</div>
                                    <div style="color: #1f2937; font-weight: 600; font-size: 0.95rem;">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="welcome-subtitle">
                        Ready to challenge yourself today? Choose an exam mode below to get started.
                    </div>

                    <div class="quick-stats">
                        <div class="stat-box">
                            <i class="bi bi-book"></i>
                            <div class="stat-value">{{ $schoolSubjects->count() }}</div>
                            <div class="stat-label">Available Subjects</div>
                        </div>
                        <div class="stat-box">
                            <i class="bi bi-clock"></i>
                            <div class="stat-value">{{ $activeSessions->count() }}</div>
                            <div class="stat-label">Active Sessions</div>
                        </div>
                        <div class="stat-box">
                            <i class="bi bi-trophy"></i>
                            <div class="stat-value">Ready</div>
                            <div class="stat-label">Your Status</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Sessions -->
            @if($activeSessions->isNotEmpty())
            <div class="exam-section">
                <h3><i class="bi bi-play-circle-fill"></i> Continue Your Exams</h3>
                <div class="session-grid">
                    @foreach($activeSessions as $session)
                        <div class="session-card">
                            <span class="session-badge ongoing">
                                <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> In Progress
                            </span>
                            <div class="session-title">
                                {{ $session->exam_mode === 'jamb' ? 'JAMB Mock Exam' : $session->subject->name }}
                            </div>
                            <div class="session-info">
                                <div><i class="bi bi-calendar3"></i> Started: {{ $session->started_at->format('M d, H:i') }}</div>
                                <div><i class="bi bi-question-circle"></i> {{ $session->total_questions }} questions</div>
                                <div><i class="bi bi-alarm"></i> {{ $session->duration_minutes }} minutes</div>
                            </div>
                            <div class="session-actions">
                                <a href="{{ route('exam.take', $session) }}" class="btn-continue">
                                    <i class="bi bi-play-fill"></i> Continue Exam
                                </a>
                                <form method="POST" action="{{ route('exam.terminate', $session) }}" onsubmit="return confirm('Terminate this exam? This will end the session and mark it as completed.');">
                                    @csrf
                                    <button type="submit" class="btn-continue btn-terminate">
                                        <i class="bi bi-x-circle-fill"></i> Terminate Exam
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- School Exam -->
            <div class="exam-section">
                <h3><i class="bi bi-journal-text"></i> Start New Exam</h3>
                
                <div class="exam-card">
                    <span class="exam-badge school">SCHOOL MODE</span>
                    <h4>School Mock Examination</h4>
                    <div class="exam-info">
                        <div class="exam-info-item">
                            <i class="bi bi-question-circle-fill"></i>
                            <span>{{ $schoolQuestionsCount }} Questions</span>
                        </div>
                        <div class="exam-info-item">
                            <i class="bi bi-clock-fill"></i>
                            <span>{{ $schoolDurationMinutes }} Minutes</span>
                        </div>
                        <div class="exam-info-item">
                            <i class="bi bi-book-fill"></i>
                            <span>Single Subject</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('exam.start') }}">
                        @csrf
                        <label class="form-label fw-bold">Select a Subject</label>
                        @if($schoolSubjects->count() > 0)
                            <select class="form-select form-select-lg mb-3" name="subject_id" required>
                                <option value="">Choose your subject...</option>
                                @foreach($schoolSubjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-exam">
                                <i class="bi bi-play-circle-fill"></i>
                                <span>Start School Exam</span>
                            </button>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                No subjects available with enough questions. Please contact your administrator.
                            </div>
                        @endif
                    </form>
                </div>

                <!-- JAMB Exam -->
                <div class="exam-card">
                    <span class="exam-badge jamb">JAMB MODE</span>
                    <h4>JAMB Mock Examination</h4>
                    <div class="exam-info">
                        <div class="exam-info-item">
                            <i class="bi bi-question-circle-fill"></i>
                            <span>{{ $jambTotalQuestions }} Questions</span>
                        </div>
                        <div class="exam-info-item">
                            <i class="bi bi-clock-fill"></i>
                            <span>{{ $jambDurationMinutes }} Minutes</span>
                        </div>
                        <div class="exam-info-item">
                            <i class="bi bi-book-fill"></i>
                            <span>4 Subjects (English + 3 others)</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('exam.start.jamb') }}">
                        @csrf
                        <label class="form-label fw-bold">Select 3 Subjects (English is automatic)</label>
                        @if($jambSubjects->count() >= 3)
                            <div class="checkbox-group">
                                @foreach($jambSubjects as $subject)
                                    <label class="form-check" for="subject_{{ $subject->id }}">
                                        <input class="form-check-input" type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" id="subject_{{ $subject->id }}">
                                        <span class="form-check-label">{{ $subject->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit" class="btn-exam mt-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="bi bi-lightning-charge-fill"></i>
                                <span>Start JAMB Exam</span>
                            </button>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                Need at least 3 subjects with enough questions. 
                                @if($jambSubjects->count() > 0)
                                    Currently available: {{ $jambSubjects->count() }} subject(s)
                                @else
                                    Please contact your administrator.
                                @endif
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Force reload when returning via browser back/forward cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // JAMB subject selection limit (exactly 3)
        document.addEventListener('DOMContentLoaded', function() {
            const jambCheckboxes = document.querySelectorAll('input[name="subject_ids[]"]');
            const jambSubmitBtn = document.querySelector('form[action="{{ route('exam.start.jamb') }}"] button[type="submit"]');
            const maxSelections = 3;

            if (jambCheckboxes.length > 0 && jambSubmitBtn) {
                function updateJambSelection() {
                    const checked = Array.from(jambCheckboxes).filter(cb => cb.checked);
                    const checkedCount = checked.length;

                    // Disable unchecked boxes if 3 are already selected
                    jambCheckboxes.forEach(checkbox => {
                        if (!checkbox.checked && checkedCount >= maxSelections) {
                            checkbox.disabled = true;
                            checkbox.parentElement.style.opacity = '0.5';
                        } else if (checkbox.disabled) {
                            checkbox.disabled = false;
                            checkbox.parentElement.style.opacity = '1';
                        }
                    });

                    // Enable/disable submit button based on exact count
                    if (checkedCount === maxSelections) {
                        jambSubmitBtn.disabled = false;
                        jambSubmitBtn.style.opacity = '1';
                    } else {
                        jambSubmitBtn.disabled = true;
                        jambSubmitBtn.style.opacity = '0.6';
                    }
                }

                // Attach event listeners
                jambCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateJambSelection);
                });

                // Initial state
                updateJambSelection();
            }

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
        });
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
