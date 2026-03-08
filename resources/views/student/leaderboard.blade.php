<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leaderboard - School CBT</title>
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
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            transform: translateX(0);
            z-index: 1040;
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
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

        .menu-item:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
            white-space: nowrap;
        }

        /* Sidebar visibility */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0) !important;
                z-index: 1040;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                /* Span full height and width on mobile */
                top: 0 !important;
                bottom: 0 !important;
                height: 100vh;
                background: rgba(255, 255, 255, 0.98);
                transform: translateX(-100%) !important;
                z-index: 1050;
            }
            .sidebar.show {
                transform: translateX(0) !important;
            }
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            transition: padding 0.25s ease;
            width: 100%;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .top-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 100;
        }

        .sidebar-toggle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #374151;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
            z-index: 101;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .sidebar-toggle:active {
            background: #f3f4f6;
        }

        /* Desktop - Hide toggle */
        @media (min-width: 992px) {
            .sidebar-toggle {
                display: none !important;
            }
        }

        /* Tablet & Below - Show toggle */
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }

            .sidebar-toggle {
                display: flex !important;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            h1 {
                font-size: 1.5rem !important;
            }

            .leaderboard-card {
                padding: 1rem;
                margin-bottom: 0.75rem;
                gap: 1rem !important;
            }

            .rank-badge {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1rem;
            }

            .leaderboard-card strong {
                font-size: 0.95rem;
            }

            .leaderboard-card small {
                font-size: 0.8rem;
            }

            hr {
                margin: 1.5rem 0 !important;
            }

            h5 {
                font-size: 1.1rem !important;
            }
        }

        /* Small Mobile (max-width: 480px) */
        @media (max-width: 480px) {
            .main-content {
                padding: 0.75rem;
            }

            h1 {
                font-size: 1.25rem !important;
            }

            p {
                font-size: 0.85rem !important;
            }

            .alert {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .leaderboard-card {
                padding: 0.75rem;
                margin-bottom: 0.5rem;
                gap: 0.75rem !important;
                flex-wrap: wrap;
            }

            .rank-badge {
                width: 2.25rem;
                height: 2.25rem;
                font-size: 0.9rem;
                flex-shrink: 0;
            }

            .flex-grow-1 {
                flex: 1 1 100%;
                min-width: 0;
            }

            .leaderboard-card strong {
                font-size: 0.9rem;
                word-break: break-word;
            }

            .leaderboard-card small {
                font-size: 0.75rem;
            }
        }

        .leaderboard-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }

        .leaderboard-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .rank-badge {
            font-size: 1.25rem;
            font-weight: 700;
            width: 3rem;
            height: 3rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 12px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        @include('student.partials.sidebar')
        <main class="main-content">
            <div class="top-actions">
                <button class="sidebar-toggle" id="sidebarToggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <div>
                <h1 class="mb-2" style="color: white;">Leaderboard</h1>
                <p class="text-white-50 mb-4">Top 10 students. If you're ranked between 11 and 30, your own position will appear below.</p>

                @if($leaders->isEmpty())
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No results yet.
                    </div>
                @else
                    {{-- show the ten leaders --}}
                    @foreach($leaders as $i => $session)
                        <div class="leaderboard-card d-flex align-items-center gap-3">
                            <div class="rank-badge">{{ $i+1 }}</div>
                            <div class="flex-grow-1">
                                <strong>{{ $session->student->name }}</strong><br>
                                <small class="text-muted">Score: {{ $session->score }} &middot; {{ $session->completed_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach

                    {{-- if the current user is outside the top 10 but still within the top 30, show their rank --}}
                    @if(isset($userRank) && $userRank > 10 && $userRank <= 30 && isset($userSession))
                        <hr class="bg-white-50 my-4">
                        <h5 style="color: white; margin-bottom: 1.5rem;">Your Position</h5>
                        <div class="leaderboard-card d-flex align-items-center gap-3" style="border: 2px solid var(--primary);">
                            <div class="rank-badge">{{ $userRank }}</div>
                            <div class="flex-grow-1">
                                <strong>{{ auth()->user()->name }}</strong><br>
                                <small class="text-muted">Score: {{ $userSession->score }} &middot; {{ $userSession->completed_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </main>
    </div>

    <script>
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.getElementById('sidebarToggle');

            if (toggle && sidebar) {
                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                });

                // Close sidebar when clicking outside on mobile
                if (sidebar) {
                    document.addEventListener('click', function (e) {
                        if (window.innerWidth < 992) {
                            if (!sidebar.contains(e.target) && e.target !== toggle && !toggle?.contains(e.target)) {
                                sidebar.classList.remove('show');
                            }
                        }
                    });
                }

                // Close sidebar on resize to desktop
                window.addEventListener('resize', function () {
                    if (window.innerWidth >= 992) {
                        sidebar?.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>
</html>