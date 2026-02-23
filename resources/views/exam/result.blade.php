<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exam Result - School CBT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .result-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .result-header {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .result-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .result-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            position: relative;
        }

        .result-icon.excellent {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .result-icon.good {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .result-icon.average {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .result-icon.poor {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .result-badge {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .result-badge.school {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .result-badge.jamb {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .result-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .result-score {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 1rem 0;
        }

        .result-score.excellent { color: #10b981; }
        .result-score.good { color: #3b82f6; }
        .result-score.average { color: #f59e0b; }
        .result-score.poor { color: #ef4444; }

        .result-meta {
            display: flex;
            justify-content: center;
            gap: 2rem;
            color: #6b7280;
            margin-top: 1.5rem;
        }

        .result-meta i {
            color: #4f46e5;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
        }

        .stat-icon.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .subject-breakdown {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .subject-breakdown h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        .subject-item {
            padding: 1.25rem;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .subject-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .subject-info {
            flex: 1;
        }

        .subject-name {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .subject-correct {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .subject-score {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4f46e5;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-action {
            flex: 1;
            min-width: 200px;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.125rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #4f46e5;
            border: 2px solid #4f46e5;
        }

        .btn-secondary:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-2px);
        }

        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(6, 182, 212, 0.4);
            color: white;
        }

        .performance-message {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: 4px solid;
        }

        .performance-message.excellent { border-color: #10b981; background: #f0fdf4; }
        .performance-message.good { border-color: #3b82f6; background: #eff6ff; }
        .performance-message.average { border-color: #f59e0b; background: #fffbeb; }
        .performance-message.poor { border-color: #ef4444; background: #fef2f2; }
    </style>
</head>
<body>
    <div class="result-container">
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Warning:</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $maxScore = $session->exam_mode === 'jamb' ? 400 : max(1, (int) $session->total_questions);
            $percentage = ($session->score / $maxScore) * 100;
            $completedAt = $session->completed_at
                ? $session->completed_at->timezone(config('app.timezone'))
                : null;

            if ($percentage >= 80) {
                $performanceClass = 'excellent';
                $performanceMessage = '🎉 Outstanding Performance! You\'ve demonstrated excellent mastery of the subject matter.';
                $performanceIcon = 'bi-trophy-fill';
            } elseif ($percentage >= 60) {
                $performanceClass = 'good';
                $performanceMessage = '👍 Great Work! You have a strong grasp of the content. Keep it up!';
                $performanceIcon = 'bi-star-fill';
            } elseif ($percentage >= 40) {
                $performanceClass = 'average';
                $performanceMessage = '📚 Fair Performance. You\'re close - a little more revision will boost your score.';
                $performanceIcon = 'bi-graph-up';
            } else {
                $performanceClass = 'poor';
                $performanceMessage = '💪 Keep Trying! Don\'t be discouraged. Review the material and try again - you\'ll improve!';
                $performanceIcon = 'bi-arrow-repeat';
            }
        @endphp

        <div class="result-header">
            <div class="result-icon {{ $performanceClass }}">
                <i class="bi {{ $performanceIcon }}"></i>
            </div>
            <span class="result-badge {{ $session->exam_mode }}">{{ strtoupper($session->exam_mode) }} MODE</span>
            <div class="result-title">
                {{ $session->exam_mode === 'jamb' ? 'JAMB Mock Exam' : $session->subject->name }}
            </div>
            <div class="result-score {{ $performanceClass }}">
                @if($session->exam_mode === 'jamb')
                    {{ number_format($session->score, 0) }}/400
                @else
                    {{ $session->score }}/{{ $session->total_questions }}
                @endif
            </div>
            <div style="font-size: 1.25rem; color: #6b7280; font-weight: 600;">
                {{ number_format($percentage, 1) }}%
            </div>
            <div class="result-meta">
                <span><i class="bi bi-calendar3"></i> {{ $completedAt?->format('M d, Y') }}</span>
                <span><i class="bi bi-clock"></i> {{ $completedAt?->format('H:i A') }}</span>
            </div>
        </div>

        <div class="performance-message {{ $performanceClass }}">
            <strong>{{ $performanceMessage }}</strong>
        </div>

        @if($session->exam_mode === 'school')
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="bi bi-question-circle-fill"></i>
                    </div>
                    <div class="stat-value">{{ $session->total_questions }}</div>
                    <div class="stat-label">Total Questions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="stat-value">{{ $session->score }}</div>
                    <div class="stat-label">Correct Answers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="stat-value">{{ $session->total_questions - $session->score }}</div>
                    <div class="stat-label">Incorrect Answers</div>
                </div>
            </div>
        @endif

        @if($session->exam_mode === 'jamb' && $session->examSubjectScores->isNotEmpty())
            <div class="subject-breakdown">
                <h3><i class="bi bi-bar-chart-fill"></i> Subject Performance Breakdown</h3>
                @foreach($session->examSubjectScores as $subjectScore)
                    <div class="subject-item">
                        <div class="subject-icon">
                            <i class="bi bi-book-fill"></i>
                        </div>
                        <div class="subject-info">
                            <div class="subject-name">{{ $subjectScore->subject->name }}</div>
                            <div class="subject-correct">
                                <i class="bi bi-check-circle"></i> {{ $subjectScore->correct_count }} correct answers
                            </div>
                        </div>
                        <div class="subject-score">{{ number_format($subjectScore->score_over_100, 1) }}/100</div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('student.dashboard') }}" class="btn-action btn-primary">
                <i class="bi bi-house-door-fill"></i> Back to Dashboard
            </a>
            <a href="{{ route('student.dashboard') }}" class="btn-action btn-secondary">
                <i class="bi bi-plus-circle-fill"></i> Start New Exam
            </a>
            @if($allowReview ?? false)
                <a href="{{ route('exam.review', $session) }}" class="btn-action btn-info">
                    <i class="bi bi-eye-fill"></i> Review Answers
                </a>
            @endif
            <a href="{{ route('student.history') }}" class="btn-action btn-secondary">
                <i class="bi bi-clock-history"></i> View History
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent back button from returning to exam
        (function() {
            // Clear the session storage flag that might be set
            sessionStorage.removeItem('examInProgress');
            
            // Disable back button
            if (window.history && window.history.pushState) {
                // Push current state
                window.history.pushState(null, '', window.location.href);
                
                // Listen for back button
                window.addEventListener('popstate', function(event) {
                    // Push state again to prevent going back
                    window.history.pushState(null, '', window.location.href);
                    
                    // Show friendly alert
                    alert('You cannot go back to the exam after submission. Please use the navigation buttons above.');
                });
            }
        })();
    </script>
</body>
</html>
