<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School CBT - @yield('title', 'Exam')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Serif+4:wght@400;600&display=swap">
    <style>
        :root {
            --sidebar-width: 300px;
            --navbar-height: 60px;
            --exam-ink: #0f172a;
            --exam-muted: #64748b;
            --exam-paper: #ffffff;
            --exam-surface: #f8fafc;
            --exam-accent: #1e3a8a;
            --exam-accent-2: #fbbf24;
            --exam-border: #e2e8f0;
            --exam-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            --exam-radius: 16px;
        }
        
        body {
            overflow-x: hidden;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        .exam-navbar {
            background: linear-gradient(120deg, #1e3a8a, #0f172a);
            color: white;
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            font-family: "Space Grotesk", sans-serif;
            flex-wrap: wrap;
            min-height: auto;
        }
        
        .exam-navbar > div {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .exam-navbar .exam-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
        }
        
        .exam-navbar .timer {
            font-size: 1rem;
            font-weight: 700;
            color: #fde68a;
            padding: 0.4rem 0.8rem;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .exam-navbar .timer.paused {
            background: rgba(251, 191, 36, 0.2);
            animation: pausePulse 2s ease-in-out infinite;
        }

        @keyframes pausePulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.05);
            }
        }
        
        .exam-sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            bottom: 0;
            background: var(--exam-surface);
            border-right: 1px solid var(--exam-border);
            overflow-y: auto;
            padding: 1.5rem;
            z-index: 1025;
            font-family: "Space Grotesk", sans-serif;
            min-width: 0;
        }
        
        .exam-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 2rem 1.5rem 2rem;
            min-height: calc(100vh - var(--navbar-height));
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
            position: relative;
            font-family: "Source Serif 4", serif;
        }

        .exam-content::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(30, 58, 138, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 58, 138, 0.04) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            opacity: 0.35;
        }

        .exam-content > * {
            position: relative;
            z-index: 1;
        }
        
        .subject-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #e0e7ff;
            color: #1e3a8a;
            border-radius: 999px;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid var(--exam-border);
        }
        
        .question-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .question-num {
            aspect-ratio: 1;
            border: 1px solid var(--exam-border);
            background: var(--exam-paper);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
            color: var(--exam-ink);
        }
        
        .question-num:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.12);
        }
        
        .question-num.answered {
            background: var(--exam-accent);
            color: white;
            border-color: var(--exam-accent);
        }
        
        .question-num.flagged {
            background: #fde68a;
            color: #7c4a03;
            border-color: #fbbf24;
        }
        
        .question-num.current {
            border-color: #1f2937;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.15);
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: var(--exam-muted);
        }
        
        .legend-box {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid;
        }
        
        .legend-box.answered {
            background: var(--exam-accent);
            border-color: var(--exam-accent);
        }
        
        .legend-box.flagged {
            background: #fde68a;
            border-color: #fbbf24;
        }
        
        .legend-box.unanswered {
            background: var(--exam-paper);
            border-color: var(--exam-border);
        }
        
        .question-card {
            background: var(--exam-paper);
            border-radius: var(--exam-radius);
            padding: 1.75rem 1.5rem;
            box-shadow: var(--exam-shadow);
            border: 1px solid var(--exam-border);
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .question-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .question-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            border: 1px solid var(--exam-border);
            background: #e0e7ff;
            color: #1e3a8a;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-family: "Space Grotesk", sans-serif;
        }

        .question-chip.alt {
            background: #dbeafe;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }
        
        .question-text {
            font-size: 1.2rem;
            line-height: 1.7;
            margin-bottom: 0.75rem;
            color: var(--exam-ink);
        }

        .question-instruction {
            font-size: 0.9rem;
            color: var(--exam-muted);
            margin-bottom: 1.5rem;
            font-family: "Space Grotesk", sans-serif;
            letter-spacing: 0.02em;
        }
        
        .option-label {
            display: grid;
            grid-template-columns: auto auto 1fr;
            gap: 0.5rem;
            align-items: start;
            padding: 0.8rem 1rem;
            border: 1px solid var(--exam-border);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            background: #ffffff;
            min-height: 3rem;
        }
        
        .option-label:hover {
            border-color: var(--exam-accent);
            background: #eff6ff;
        }
        
        .option-label input[type="radio"] {
            margin-top: 0.2rem;
        }
        
        .option-label input[type="radio"]:checked ~ .option-text {
            font-weight: 600;
        }
        
        .option-label:has(input[type="radio"]:checked) {
            border-color: var(--exam-accent);
            background: #dbeafe;
        }

        .option-letter {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e2e8f0;
            color: var(--exam-ink);
            font-weight: 700;
            font-family: "Space Grotesk", sans-serif;
            font-size: 0.9rem;
            border: 1px solid var(--exam-border);
        }

        .option-text {
            display: block;
            color: var(--exam-ink);
        }
        
        .btn-flag {
            background: #fff7ed;
            border: 1px solid #fbbf24;
            color: #7c4a03;
            padding: 0.5rem 1.25rem;
            border-radius: 999px;
            font-weight: 600;
            transition: all 0.2s;
            font-family: "Space Grotesk", sans-serif;
        }
        
        .btn-flag:hover {
            background: #ffedd5;
        }
        
        .btn-flag.flagged {
            background: #fde68a;
            color: #7c4a03;
            border-color: #fbbf24;
        }
        
        .navigation-buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .navigation-buttons .btn {
            flex: 1;
            min-width: 120px;
            padding: 0.6rem 1rem;
            font-size: 1rem;
        }

        .sidebar-card {
            background: var(--exam-paper);
            border-radius: 14px;
            border: 1px solid var(--exam-border);
            padding: 1.1rem 1.2rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
        }

        .sidebar-title {
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--exam-muted);
            margin-bottom: 0.85rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--exam-muted);
        }

        .info-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--exam-ink);
            margin-top: 0.15rem;
        }
        
        @media (max-width: 991px) {
            :root {
                --sidebar-width: 0;
            }
            
            .exam-sidebar {
                left: -300px;
                width: 300px;
                transition: left 0.3s ease;
                box-shadow: 2px 0 20px rgba(15, 23, 42, 0.15);
            }
            
            .exam-sidebar.show {
                left: 0;
            }
            
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: var(--navbar-height);
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1023;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            .exam-content {
                margin-left: 0;
                padding: 1.5rem 1rem;
            }
            
            .mobile-toggle {
                display: block !important;
            }
            
            .question-grid {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }
        
        .mobile-toggle {
            display: none;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .mobile-toggle:hover {
            background: rgba(255,255,255,0.3);
        }
        
        kbd {
            padding: 0.2rem 0.4rem;
            font-size: 0.875rem;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            font-family: monospace;
        }
        
        .progress-indicator {
            height: 4px;
            background: #e2e8f0;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
        
        .progress-indicator-fill {
            height: 100%;
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
            transition: width 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .exam-navbar {
                height: auto;
                min-height: var(--navbar-height);
                padding: 0.5rem 0.75rem;
                gap: 0.5rem;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
            }
            
            .exam-navbar > div {
                width: 100%;
                justify-content: space-between;
            }
            
            .exam-navbar .exam-title {
                font-size: 0.95rem;
            }
            
            .exam-navbar .timer {
                font-size: 0.85rem;
                padding: 0.3rem 0.6rem;
            }
            
            .exam-navbar .btn {
                padding: 0.4rem 0.6rem !important;
                font-size: 0.8rem !important;
            }
            
            .exam-content {
                padding: 1rem 0.75rem;
            }
            
            .question-card {
                padding: 1.25rem 1rem;
            }
            
            .question-header {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .btn-flag {
                padding: 0.4rem 0.75rem;
                font-size: 0.8rem;
            }
            
            .question-text {
                font-size: 1rem;
                line-height: 1.6;
            }
            
            .option-label {
                grid-template-columns: auto auto 1fr;
                padding: 0.7rem 0.85rem;
                gap: 0.4rem;
                min-height: auto;
            }
            
            .option-letter {
                width: 24px;
                height: 24px;
                font-size: 0.8rem;
            }
            
            .option-text {
                font-size: 0.95rem;
            }
            
            .navigation-buttons {
                gap: 0.5rem;
            }
            
            .navigation-buttons .btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
            }
            
            .sidebar-card {
                padding: 0.9rem 1rem;
                margin-bottom: 1rem;
            }
            
            .sidebar-title {
                font-size: 0.85rem;
            }
            
            .question-grid {
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 0.35rem;
            }
            
            .question-num {
                font-size: 0.8rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .info-value {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 480px) {
            .exam-navbar {
                min-height: 50px;
                padding: 0.4rem 0.5rem;
            }
            
            .exam-navbar .exam-title {
                font-size: 0.8rem;
            }
            
            .exam-navbar .timer {
                font-size: 0.75rem;
                padding: 0.2rem 0.5rem;
            }
            
            .exam-navbar .btn {
                padding: 0.3rem 0.4rem !important;
                font-size: 0.7rem !important;
            }
            
            .exam-content {
                padding: 0.75rem 0.5rem;
            }
            
            .question-card {
                padding: 1rem 0.75rem;
            }
            
            .question-text {
                font-size: 0.95rem;
            }
            
            .navigation-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .navigation-buttons .btn {
                width: 100%;
            }
            
            .navigation-buttons .ms-auto {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .question-grid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 0.25rem;
            }
            
            .question-num {
                font-size: 0.65rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <!-- Footer with Copyright -->
    <footer style="background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 1rem 2rem; text-align: center; margin-top: 2rem;">
        <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
            <strong style="color: #1f2937;">CBT Platform</strong> © 2026 El-Bethel Digital Learning Systems.
        </p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
