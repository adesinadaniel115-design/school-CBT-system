<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Review - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .review-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .review-header {
            text-align: center;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 2rem;
        }

        .review-header h1 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .review-stats {
            display: flex;
            gap: 2rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #718096;
        }

        .question-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .question-card.correct {
            border-color: #48bb78;
            background: #f0fff4;
        }

        .question-card.incorrect {
            border-color: #f56565;
            background: #fff5f5;
        }

        .question-card.unanswered {
            border-color: #ed8936;
            background: #fffaf0;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .question-number {
            font-weight: 700;
            color: #2d3748;
            font-size: 1.1rem;
        }

        .question-status {
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        .status-correct {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-incorrect {
            background: #fed7d7;
            color: #742a2a;
        }

        .status-unanswered {
            background: #feebc8;
            color: #7c2d12;
        }

        .question-text {
            font-size: 1.05rem;
            color: #2d3748;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .subject-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .option-item {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            background: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .option-item.selected {
            border-color: #667eea;
            background: #edf2f7;
            font-weight: 600;
        }

        .option-item.correct-answer {
            border-color: #48bb78;
            background: #f0fff4;
            font-weight: 600;
        }

        .option-item.wrong-selected {
            border-color: #f56565;
            background: #fff5f5;
            font-weight: 600;
        }

        .option-indicator {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .indicator-correct {
            background: #48bb78;
            color: white;
        }

        .indicator-wrong {
            background: #f56565;
            color: white;
        }

        .explanation-box {
            margin-top: 1rem;
            padding: 1rem;
            background: #edf2f7;
            border-left: 4px solid #667eea;
            border-radius: 8px;
        }

        .explanation-box strong {
            color: #667eea;
            display: block;
            margin-bottom: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f0f0f0;
        }

        .btn-action {
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #718096;
            color: white;
        }

        .btn-secondary:hover {
            background: #4a5568;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .review-container {
                padding: 1rem;
                margin: 0 1rem;
            }

            .review-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="review-container">
        <div class="review-header">
            <h1><i class="bi bi-eye-fill text-primary"></i> Exam Review</h1>
            <p class="text-muted mb-0">
                @if($session->exam_mode === 'jamb')
                    <strong>JAMB Mode</strong> - Multiple Subjects
                @else
                    <strong>School Mode</strong> - {{ $session->subject->name }}
                @endif
            </p>
            
            <div class="review-stats">
                <div class="stat-item">
                    <div class="stat-value text-success">{{ $answers->where('is_correct', true)->count() }}</div>
                    <div class="stat-label">Correct</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value text-danger">{{ $answers->where('is_correct', false)->whereNotNull('selected_option')->count() }}</div>
                    <div class="stat-label">Incorrect</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value text-warning">{{ $answers->whereNull('selected_option')->count() }}</div>
                    <div class="stat-label">Unanswered</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ number_format(($answers->where('is_correct', true)->count() / $session->total_questions) * 100, 1) }}%</div>
                    <div class="stat-label">Score</div>
                </div>
            </div>
        </div>

        @foreach($questions as $index => $question)
            @php
                $answer = $answers->get($question->id);
                $selected = $answer?->selected_option;
                $isCorrect = $answer?->is_correct ?? false;
                $isUnanswered = empty($selected);
                
                $cardClass = $isUnanswered ? 'unanswered' : ($isCorrect ? 'correct' : 'incorrect');
                $statusClass = $isUnanswered ? 'status-unanswered' : ($isCorrect ? 'status-correct' : 'status-incorrect');
                $statusText = $isUnanswered ? 'Unanswered' : ($isCorrect ? '✓ Correct' : '✗ Incorrect');
                
                // Check if this is the first question in a passage group
                $showPassage = false;
                if ($question->passage_text) {
                    if ($index === 0) {
                        $showPassage = true;
                    } else {
                        $prevQuestion = $questions[$index - 1];
                        if ($prevQuestion->passage_group !== $question->passage_group) {
                            $showPassage = true;
                        }
                    }
                }
            @endphp

            <div class="question-card {{ $cardClass }}">
                <div class="question-header">
                    <span class="question-number">Question {{ $index + 1 }}</span>
                    <span class="question-status {{ $statusClass }}">{{ $statusText }}</span>
                </div>

                @if($session->exam_mode === 'jamb')
                    <span class="subject-badge">{{ $question->subject->name }}</span>
                @endif

                {{-- Display passage if this is the first question in a group --}}
                @if($showPassage && $question->passage_text)
                    <div class="passage-container">
                        <div class="passage-header">
                            <i class="bi bi-file-text-fill"></i>
                            <span>Reading Passage / Context</span>
                        </div>
                        <div class="passage-content">
                            {!! $question->passage_text !!}
                        </div>
                    </div>
                @endif

                <div class="question-text">
                    {!! $question->question_text !!}
                </div>

                <div class="options-list">
                    @foreach(['A', 'B', 'C', 'D'] as $option)
                        @php
                            $optionText = 'option_' . strtolower($option);
                            $isCorrectAnswer = $question->correct_option === $option;
                            $isSelected = $selected === $option;
                            
                            $optionClass = '';
                            if ($isCorrectAnswer) {
                                $optionClass = 'correct-answer';
                            } elseif ($isSelected && !$isCorrectAnswer) {
                                $optionClass = 'wrong-selected';
                            } elseif ($isSelected) {
                                $optionClass = 'selected';
                            }
                        @endphp

                        <div class="option-item {{ $optionClass }}">
                            @if($isCorrectAnswer)
                                <span class="option-indicator indicator-correct">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                            @elseif($isSelected && !$isCorrectAnswer)
                                <span class="option-indicator indicator-wrong">
                                    <i class="bi bi-x-lg"></i>
                                </span>
                            @endif
                            <span><strong>{{ $option }}.</strong> {!! $question->$optionText !!}</span>
                        </div>
                    @endforeach
                </div>

                @if($question->explanation)
                    <div class="explanation-box">
                        <strong><i class="bi bi-lightbulb-fill"></i> Explanation:</strong>
                        {!! $question->explanation !!}
                    </div>
                @endif
            </div>
        @endforeach

        <div class="action-buttons">
            <a href="{{ route('exam.result', $session) }}" class="btn-action btn-primary">
                <i class="bi bi-arrow-left"></i> Back to Results
            </a>
            <a href="{{ route('student.dashboard') }}" class="btn-action btn-secondary">
                <i class="bi bi-house-door-fill"></i> Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
