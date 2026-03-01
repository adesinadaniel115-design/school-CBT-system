@extends('layouts.student')

@section('title', $session->exam_mode === 'jamb' ? 'JAMB Mock Exam' : $session->subject->name . ' Exam')

@push('styles')
<style>
    .autosave-toast {
        position: fixed;
        right: 1.5rem;
        bottom: 1.5rem;
        background: rgba(16, 185, 129, 0.95);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        box-shadow: 0 10px 24px rgba(16, 185, 129, 0.25);
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.2s ease, transform 0.2s ease;
        pointer-events: none;
        z-index: 1050;
    }

    .autosave-toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Passage/Comprehension Styling */
    .passage-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #0d6efd;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .passage-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        color: #0d6efd;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .passage-header i {
        font-size: 1.25rem;
    }

    .passage-content {
        background: white;
        padding: 1.25rem;
        border-radius: 8px;
        line-height: 1.8;
        color: #1f2937;
        font-size: 1rem;
        white-space: pre-wrap;
        border: 1px solid #e5e7eb;
    }

    .passage-content strong {
        font-weight: 700;
        color: #111827;
    }

    /* Scientific Calculator Styles */
    .calculator-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        z-index: 1040;
        align-items: flex-end;
        justify-content: center;
        padding: 1rem;
        backdrop-filter: blur(4px);
    }

    .calculator-overlay.show {
        display: flex;
    }

    .calculator-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.3);
        width: 100%;
        max-width: 320px; /* make compact */
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .calculator-header {
        background: linear-gradient(120deg, #1e3a8a, #0f172a);
        color: white;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(30, 58, 138, 0.1);
    }

    .calculator-header h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    .calculator-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .calculator-header .btn-close:hover {
        opacity: 1;
    }

    .calculator-display {
        padding: 1rem 1.25rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .calc-screen {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1.5rem;
        font-weight: 600;
        text-align: right;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        color: #0f172a;
        font-family: "Courier New", monospace;
        word-wrap: break-word;
        word-break: break-all;
        max-height: 80px;
        overflow-y: auto;
    }

    .calc-screen:focus {
        outline: none;
        border-color: #1e3a8a;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .calculator-buttons {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        padding: 1rem;
        background: white;
    }

    .calc-btn {
        padding: 1rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f1f5f9;
        color: #0f172a;
        border: 1px solid #e2e8f0;
    }

    .calc-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }

    .calc-btn:active {
        transform: translateY(0);
        box-shadow: inset 0 2px 4px rgba(15, 23, 42, 0.1);
    }

    .calc-btn.op {
        background: #e0e7ff;
        color: #1e3a8a;
        border-color: #c7d2fe;
        font-weight: 700;
    }

    .calc-btn.op:hover {
        background: #c7d2fe;
    }

    .calc-btn.func {
        background: #dbeafe;
        color: #0369a1;
        border-color: #bae6fd;
        font-size: 0.85rem;
    }

    .calc-btn.func:hover {
        background: #bae6fd;
    }

    .calc-btn.clear {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
        font-weight: 700;
    }

    .calc-btn.clear:hover {
        background: #fca5a5;
    }

    .calc-btn.equals {
        background: linear-gradient(120deg, #1e3a8a, #0f172a);
        color: white;
        border: none;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .calc-btn.equals:hover {
        opacity: 0.9;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .calculator-container {
            max-width: 100%;
        }

        .calc-btn {
            padding: 0.85rem 0.5rem;
            font-size: 0.85rem;
        }

        .calculator-buttons {
            gap: 0.35rem;
            padding: 0.75rem;
        }

        .calc-screen {
            font-size: 1.25rem;
        }
        
        .autosave-toast {
            right: 1rem;
            bottom: 1rem;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 480px) {
        .autosave-toast {
            right: 0.75rem;
            bottom: 0.75rem;
            padding: 0.6rem 0.8rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Top Navbar -->
<nav class="exam-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i> Menu
        </button>
        <h1 class="exam-title mb-0">
            @if($session->exam_mode === 'jamb')
                <i class="bi bi-mortarboard-fill"></i> JAMB Mock Exam
            @else
                <i class="bi bi-journal-text"></i> {{ $session->subject->name }}
            @endif
        </h1>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <div class="timer" id="timer" data-started-at="{{ $session->started_at->toIso8601String() }}" data-duration-minutes="{{ $session->duration_minutes }}">
            <i class="bi bi-clock-fill"></i> <span id="timer-text">Loading...</span>
        </div>
        <button type="button" class="btn btn-light" onclick="showHelp()" title="Keyboard Shortcuts">
            <i class="bi bi-question-circle-fill"></i>
        </button>
        <button type="button" class="btn btn-danger btn-lg" onclick="confirmSubmit()">
            <i class="bi bi-check-circle-fill"></i> Submit Exam
        </button>
    </div>
    
    <div class="progress-indicator">
        <div class="progress-indicator-fill" id="progressBar" style="width: 0%;"></div>
    </div>
</nav>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-keyboard"></i> Keyboard Shortcuts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><kbd>←</kbd> <kbd>→</kbd></td>
                            <td>Navigate between questions</td>
                        </tr>
                        <tr>
                            <td><kbd>1</kbd> <kbd>2</kbd> <kbd>3</kbd> <kbd>4</kbd></td>
                            <td>Select options A, B, C, D</td>
                        </tr>
                        <tr>
                            <td><kbd>F</kbd></td>
                            <td>Flag/Unflag question</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-opacity-10">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i> 
                    Confirm Submission
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Answered:</strong>
                        <span id="modal-answered">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Unanswered:</strong>
                        <span class="text-danger" id="modal-unanswered">0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Total Questions:</strong>
                        <span id="modal-total">0</span>
                    </div>
                </div>
                <p class="mb-0">
                    <i class="bi bi-info-circle"></i> 
                    Once submitted, you cannot change your answers.
                    <strong>Are you sure you want to submit?</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="submitExam()">
                    <i class="bi bi-check-circle-fill"></i> Yes, Submit Exam
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Calculator Overlay -->
<div id="calculatorOverlay" class="calculator-overlay">
    <div class="calculator-container">
        <div class="calculator-header">
            <h5 class="mb-0">Calculator</h5>
            <button type="button" class="btn-close" onclick="toggleCalculator()" aria-label="Close calculator"></button>
        </div>
        <div class="calculator-display">
            <input type="text" id="calcDisplay" class="calc-screen" readonly value="0">
        </div>
        <div class="calculator-buttons">
            <!-- Row 1 -->
            <button class="calc-btn" onclick="appendCalc('7')">7</button>
            <button class="calc-btn" onclick="appendCalc('8')">8</button>
            <button class="calc-btn" onclick="appendCalc('9')">9</button>
            <button class="calc-btn op" onclick="appendCalc('/')">÷</button>
            <!-- Row 2 -->
            <button class="calc-btn" onclick="appendCalc('4')">4</button>
            <button class="calc-btn" onclick="appendCalc('5')">5</button>
            <button class="calc-btn" onclick="appendCalc('6')">6</button>
            <button class="calc-btn op" onclick="appendCalc('*')">×</button>
            <!-- Row 3 -->
            <button class="calc-btn" onclick="appendCalc('1')">1</button>
            <button class="calc-btn" onclick="appendCalc('2')">2</button>
            <button class="calc-btn" onclick="appendCalc('3')">3</button>
            <button class="calc-btn op" onclick="appendCalc('-')">−</button>
            <!-- Row 4 -->
            <button class="calc-btn" onclick="appendCalc('0')" style="grid-column: span 2;">0</button>
            <button class="calc-btn" onclick="appendCalc('.')">.</button>
            <button class="calc-btn op" onclick="appendCalc('+')">+</button>
            <!-- Controls -->
            <button class="calc-btn clear" onclick="clearCalculator()">C</button>
            <button class="calc-btn clear" onclick="backspaceCalculator()">←</button>
            <button class="calc-btn equals" onclick="calculateResult()" style="grid-column: span 4;">=</button>
        </div>
    </div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Left Sidebar -->
<aside class="exam-sidebar" id="examSidebar">
    <div class="sidebar-card">
        <div class="sidebar-title">Exam Info</div>
        <div class="info-grid">
            <div>
                <div class="info-label">Total Questions</div>
                <div class="info-value">{{ $session->total_questions }}</div>
            </div>
            <div>
                <div class="info-label">Duration</div>
                <div class="info-value">{{ $session->duration_minutes }}</div>
            </div>
        </div>
    </div>

    @if($session->exam_mode === 'jamb')
    <div class="sidebar-card">
        <div class="sidebar-title">Subjects</div>
        @php
            $subjectsInExam = collect();
            foreach($questions as $q) {
                if (!$subjectsInExam->contains('id', $q->subject->id)) {
                    $subjectsInExam->push($q->subject);
                }
            }
        @endphp
        @foreach($subjectsInExam as $subject)
            <div class="subject-badge w-100 text-center">
                {{ $subject->name }}
            </div>
        @endforeach
    </div>
    @endif

    <div class="sidebar-card">
        <div class="sidebar-title">Questions</div>
        <div class="question-grid" id="questionGrid">
            @foreach($questions as $index => $question)
                @php
                    $answer = $answers->get($question->id);
                    $selected = $answer ? $answer->selected_option : null;
                @endphp
                <button type="button" 
                        class="question-num {{ $selected ? 'answered' : '' }} {{ $index === 0 ? 'current' : '' }}" 
                        data-question="{{ $index }}"
                        data-answered="{{ $selected ? 'true' : 'false' }}"
                        onclick="navigateQuestion({{ $index }})">
                    {{ $index + 1 }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Calculator Button -->
    <div class="sidebar-card">
        <button type="button" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" 
                onclick="toggleCalculator()" title="Open Scientific Calculator">
            <i class="bi bi-calculator-fill"></i> Calculator
        </button>
    </div>

    <div class="sidebar-card">
        <div class="sidebar-title">Legend</div>
        <div class="legend-item">
            <div class="legend-box answered"></div>
            <span>Answered</span>
        </div>
        <div class="legend-item">
            <div class="legend-box flagged"></div>
            <span>Flagged for Review</span>
        </div>
        <div class="legend-item">
            <div class="legend-box unanswered"></div>
            <span>Not Answered</span>
        </div>
        <div class="d-flex justify-content-between text-muted small mt-3">
            <span><i class="bi bi-check-circle"></i> Answered: <strong id="answeredCount">0</strong></span>
            <span><i class="bi bi-circle"></i> Remaining: <strong id="remainingCount">{{ $session->total_questions }}</strong></span>
        </div>
    </div>
</aside>

<!-- Main Content -->
<main class="exam-content">
    <form method="POST" action="{{ route('exam.submit', $session) }}" id="exam-form">
        @csrf
        
        @foreach($questions as $index => $question)
            @php
                $answer = $answers->get($question->id);
                $selected = $answer ? $answer->selected_option : null;
                
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
            
            <div class="question-card" 
                 data-question-index="{{ $index }}" 
                 style="{{ $index === 0 ? '' : 'display: none;' }}">
                
                <div class="question-header">
                    <div class="question-meta">
                        <span class="question-chip">
                            <i class="bi bi-journal-text"></i>
                            Question {{ $index + 1 }} of {{ $session->total_questions }}
                        </span>
                        @if($session->exam_mode === 'jamb')
                            <span class="question-chip alt">
                                <i class="bi bi-book"></i>
                                {{ $question->subject->name }}
                            </span>
                        @endif
                    </div>
                    @if($allowFlagging ?? true)
                        <button type="button" 
                                class="btn-flag" 
                                data-question="{{ $index }}"
                                onclick="toggleFlag({{ $index }})">
                            <i class="bi bi-flag-fill"></i> <span class="flag-text">Flag for Review</span>
                        </button>
                    @endif
                </div>

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
                <div class="question-instruction">Choose the best answer.</div>

                <div class="options-container">
                    <label class="option-label">
                        <input type="radio" 
                               name="answers[{{ $question->id }}]" 
                               value="A" 
                               {{ $selected === 'A' ? 'checked' : '' }}
                               onchange="updateAnswerStatus({{ $index }}); saveAnswer({{ $question->id }}, {{ $index }});">
                        <span class="option-letter">A</span>
                        <span class="option-text">{!! $question->option_a !!}</span>
                    </label>

                    <label class="option-label">
                        <input type="radio" 
                               name="answers[{{ $question->id }}]" 
                               value="B" 
                               {{ $selected === 'B' ? 'checked' : '' }}
                               onchange="updateAnswerStatus({{ $index }}); saveAnswer({{ $question->id }}, {{ $index }});">
                        <span class="option-letter">B</span>
                        <span class="option-text">{!! $question->option_b !!}</span>
                    </label>

                    <label class="option-label">
                        <input type="radio" 
                               name="answers[{{ $question->id }}]" 
                               value="C" 
                               {{ $selected === 'C' ? 'checked' : '' }}
                               onchange="updateAnswerStatus({{ $index }}); saveAnswer({{ $question->id }}, {{ $index }});">
                        <span class="option-letter">C</span>
                        <span class="option-text">{!! $question->option_c !!}</span>
                    </label>

                    <label class="option-label">
                        <input type="radio" 
                               name="answers[{{ $question->id }}]" 
                               value="D" 
                               {{ $selected === 'D' ? 'checked' : '' }}
                               onchange="updateAnswerStatus({{ $index }}); saveAnswer({{ $question->id }}, {{ $index }});">
                        <span class="option-letter">D</span>
                        <span class="option-text">{!! $question->option_d !!}</span>
                    </label>
                </div>

                <div class="navigation-buttons">
                    @if($index > 0)
                        <button type="button" class="btn btn-outline-primary btn-lg" onclick="navigateQuestion({{ $index - 1 }})">
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                    @endif
                    
                    <div class="ms-auto d-flex gap-2">
                        @if($index < count($questions) - 1)
                            <button type="button" class="btn btn-primary btn-lg" onclick="navigateQuestion({{ $index + 1 }})">
                                Next <i class="bi bi-arrow-right"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-success btn-lg" onclick="confirmSubmit()">
                                <i class="bi bi-check-circle-fill"></i> Submit Exam
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </form>
</main>

<div class="autosave-toast" id="autosaveToast" role="status" aria-live="polite">
    <i class="bi bi-check-circle-fill"></i>
    <span>Saved</span>
</div>
@endsection

@push('scripts')
<script>
    let currentQuestion = 0;
    const totalQuestions = {{ count($questions) }};
    const flaggedQuestions = new Set();
    const isJamb = {{ $session->exam_mode === 'jamb' ? 'true' : 'false' }};
    let isSubmitting = false; // Flag to prevent double submission

    function navigateQuestion(index) {
        if (index < 0 || index >= totalQuestions) return;
        
        // Hide all questions
        document.querySelectorAll('.question-card').forEach(card => {
            card.style.display = 'none';
        });
        
        // Show target question
        const targetCard = document.querySelector(`.question-card[data-question-index="${index}"]`);
        if (targetCard) {
            targetCard.style.display = 'block';
            currentQuestion = index;
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Update navigation grid
        document.querySelectorAll('.question-num').forEach(btn => {
            btn.classList.remove('current');
        });
        
        const currentBtn = document.querySelector(`.question-num[data-question="${index}"]`);
        if (currentBtn) {
            currentBtn.classList.add('current');
        }
        
        // Close sidebar on mobile
        if (window.innerWidth <= 991) {
            toggleSidebar();
        }
    }

    function updateAnswerStatus(index) {
        const btn = document.querySelector(`.question-num[data-question="${index}"]`);
        const card = document.querySelector(`.question-card[data-question-index="${index}"]`);
        
        if (btn && card) {
            const hasAnswer = card.querySelector('input[type="radio"]:checked') !== null;
            
            if (hasAnswer) {
                btn.classList.add('answered');
                btn.dataset.answered = 'true';
            } else {
                btn.classList.remove('answered');
                btn.dataset.answered = 'false';
            }
            
            // Update counts
            updateCounts();
        }
    }

    function saveAnswer(questionId, index) {
        const card = document.querySelector(`.question-card[data-question-index="${index}"]`);
        if (!card) return;

        const selected = card.querySelector('input[type="radio"]:checked');
        const selectedOption = selected ? selected.value : '';
        const token = document.querySelector('input[name="_token"]')?.value;

        if (!token) {
            return;
        }

        const formData = new FormData();
        formData.append('_token', token);
        formData.append('question_id', questionId);
        formData.append('selected_option', selectedOption);

        fetch("{{ route('exam.answer', $session) }}", {
            method: 'POST',
            body: formData,
        }).then(() => {
            showAutosaveToast();
        }).catch(() => {
            // Silent fail: we do not block the UI on network issues.
        });
    }

    let autosaveTimer = null;

    function showAutosaveToast() {
        const toast = document.getElementById('autosaveToast');
        if (!toast) {
            return;
        }

        toast.classList.add('show');

        if (autosaveTimer) {
            clearTimeout(autosaveTimer);
        }

        autosaveTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 1200);
    }

    function toggleFlag(index) {
        const btn = document.querySelector(`.question-num[data-question="${index}"]`);
        const flagBtn = document.querySelector(`.btn-flag[data-question="${index}"]`);
        
        if (flaggedQuestions.has(index)) {
            flaggedQuestions.delete(index);
            btn.classList.remove('flagged');
            flagBtn.classList.remove('flagged');
            flagBtn.querySelector('.flag-text').textContent = 'Flag for Review';
        } else {
            flaggedQuestions.add(index);
            btn.classList.add('flagged');
            flagBtn.classList.add('flagged');
            flagBtn.querySelector('.flag-text').textContent = 'Flagged';
        }
    }

    function updateCounts() {
        let answered = 0;
        document.querySelectorAll('.question-num').forEach(btn => {
            if (btn.dataset.answered === 'true') {
                answered++;
            }
        });
        
        const remaining = totalQuestions - answered;
        const percentage = (answered / totalQuestions) * 100;
        
        document.getElementById('answeredCount').textContent = answered;
        document.getElementById('remainingCount').textContent = remaining;
        document.getElementById('progressBar').style.width = percentage + '%';
    }

    function confirmSubmit() {
        const answered = document.querySelectorAll('.question-num[data-answered="true"]').length;
        const unanswered = totalQuestions - answered;
        
        // Update modal content
        document.getElementById('modal-answered').textContent = answered;
        document.getElementById('modal-unanswered').textContent = unanswered;
        document.getElementById('modal-total').textContent = totalQuestions;
        
        // Show Bootstrap modal instead of browser confirm
        const submitModal = new bootstrap.Modal(document.getElementById('submitModal'));
        submitModal.show();
    }

    function submitExam() {
        // Prevent double submission
        if (isSubmitting) {
            console.log('Already submitting, please wait...');
            return;
        }
        
        isSubmitting = true;
        
        // Prevent multiple submissions
        const form = document.getElementById('exam-form');
        const submitButtons = document.querySelectorAll('button[onclick*="confirmSubmit"]');
        
        // Disable all submit buttons
        submitButtons.forEach(btn => {
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';
        });
        
        // Close modal
        const submitModal = bootstrap.Modal.getInstance(document.getElementById('submitModal'));
        if (submitModal) {
            submitModal.hide();
        }
        
        // Replace current history state to prevent back navigation
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, '', window.location.href);
            window.history.pushState(null, '', window.location.href);
            
            // Prevent back button
            window.addEventListener('popstate', function(event) {
                window.history.pushState(null, '', window.location.href);
            });
        }

        // Clear timer state so new sessions start fresh
        sessionStorage.removeItem(`exam_remaining_${{ $session->id }}`);
        sessionStorage.removeItem(`exam_pause_${{ $session->id }}`);
        
        // Submit the form
        form.submit();
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('examSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }

    function showHelp() {
        const helpModal = new bootstrap.Modal(document.getElementById('helpModal'));
        helpModal.show();
    }

    // Basic Calculator Functions
    let calcExpression = '';

    function toggleCalculator() {
        const overlay = document.getElementById('calculatorOverlay');
        overlay.classList.toggle('show');
        if (overlay.classList.contains('show')) {
            document.getElementById('calcDisplay').focus();
        }
    }

    function appendCalc(value) {
        const display = document.getElementById('calcDisplay');
        if (display.value === '0' && value !== '.') {
            display.value = value;
        } else if (display.value === '0' && value === '.') {
            display.value = '0.';
        } else {
            display.value += value;
        }
        calcExpression = display.value;
    }

    function clearCalculator() {
        document.getElementById('calcDisplay').value = '0';
        calcExpression = '';
    }

    function backspaceCalculator() {
        const display = document.getElementById('calcDisplay');
        if (display.value.length > 1) {
            display.value = display.value.slice(0, -1);
        } else {
            display.value = '0';
        }
        calcExpression = display.value;
    }

    function calculateResult() {
        const display = document.getElementById('calcDisplay');
        try {
            let expression = display.value;
            // Replace mathematical symbols with JavaScript operators
            expression = expression.replace(/×/g, '*');
            expression = expression.replace(/÷/g, '/');
            expression = expression.replace(/−/g, '-');
            
            let result = eval(expression);
            if (!isFinite(result)) {
                display.value = 'Error';
            } else {
                result = Math.round(result * 10000000000) / 10000000000;
                display.value = result;
            }
            calcExpression = display.value;
        } catch(e) {
            display.value = 'Error';
            console.error('Calculator error:', e);
        }
    }

    // Close calculator when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const calculatorOverlay = document.getElementById('calculatorOverlay');
        calculatorOverlay.addEventListener('click', function(e) {
            if (e.target === calculatorOverlay) {
                toggleCalculator();
            }
        });

        // Allow keyboard input for calculator if visible
        document.addEventListener('keydown', function(e) {
            const overlay = document.getElementById('calculatorOverlay');
            if (!overlay.classList.contains('show')) return;

            const key = e.key;

            if (key >= '0' && key <= '9') {
                appendCalc(key);
            } else if (key === '+' || key === '-' || key === '*' || key === '/') {
                appendCalc(key);
            } else if (key === '.') {
                appendCalc('.');
            } else if (key === 'Enter') {
                e.preventDefault();
                calculateResult();
            } else if (key === 'Backspace') {
                e.preventDefault();
                backspaceCalculator();
            } else if (key.toLowerCase() === 'c') {
                e.preventDefault();
                clearCalculator();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        updateCounts();
    });

    // Timer functionality with REAL pause capability
    (function () {
        const timerEl = document.getElementById('timer');
        const timerText = document.getElementById('timer-text');
        const form = document.getElementById('exam-form');
        const startedAt = new Date(timerEl.getAttribute('data-started-at'));
        const durationMinutes = parseInt(timerEl.getAttribute('data-duration-minutes'), 10);
        const totalSeconds = durationMinutes * 60;

        let isPaused = false;
        let pausedAt = null;
        let tickInterval = null;
        const remainingKey = `exam_remaining_${{ $session->id }}`;
        const pauseKey = `exam_pause_${{ $session->id }}`;

        const storedRemaining = sessionStorage.getItem(remainingKey);
        const parsedRemaining = storedRemaining ? parseInt(storedRemaining, 10) : null;
        let remainingSeconds = Number.isNaN(parsedRemaining) || parsedRemaining === null
            ? Math.max(totalSeconds - Math.floor((Date.now() - startedAt.getTime()) / 1000), 0)
            : parsedRemaining;

        function render(seconds, showPaused = false) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            let timeStr = '';
            if (hours > 0) {
                timeStr = `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            } else {
                timeStr = `${minutes}:${secs.toString().padStart(2, '0')}`;
            }
            
            if (showPaused) {
                timerText.innerHTML = `<i class="bi bi-pause-circle-fill"></i> ${timeStr} (PAUSED)`;
                timerEl.style.color = '#f59e0b';
            } else {
                timerText.textContent = timeStr;
                
                // Change color based on remaining time
                if (seconds < 300) { // Less than 5 minutes
                    timerEl.style.color = '#ef4444';
                } else if (seconds < 600) { // Less than 10 minutes
                    timerEl.style.color = '#f59e0b';
                } else {
                    timerEl.style.color = '';
                }
            }
        }

        function tick() {
            if (isPaused) {
                return; // Don't tick when paused
            }

            if (remainingSeconds <= 0) {
                timerText.textContent = '0:00';
                alert('Time is up! Your exam will be submitted automatically.');
                form.submit();
                return;
            }

            remainingSeconds = Math.max(remainingSeconds - 1, 0);
            render(remainingSeconds, false);
            sessionStorage.setItem(remainingKey, String(remainingSeconds));
        }

        function pause() {
            if (isPaused) {
                return;
            }

            isPaused = true;
            pausedAt = Date.now();
            sessionStorage.setItem(pauseKey, String(pausedAt));
            sessionStorage.setItem(remainingKey, String(remainingSeconds));
            render(remainingSeconds, true);
            timerEl.classList.add('paused'); // Add visual paused state
        }

        function resume() {
            if (!isPaused || !pausedAt) {
                return;
            }

            isPaused = false;
            pausedAt = null;
            sessionStorage.removeItem(pauseKey);
            timerEl.classList.remove('paused'); // Remove visual paused state
            render(remainingSeconds, false);
        }

        // Detect when user leaves/returns to the page
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                pause();
            } else {
                resume();
            }
        });

        // Handle window blur/focus (when switching tabs)
        window.addEventListener('blur', function() {
            pause();
        });

        window.addEventListener('focus', function() {
            resume();
        });

        // Handle page unload (closing tab/browser)
        window.addEventListener('beforeunload', function() {
            if (!isPaused) {
                pause();
            }
        });

        // Persist pause for bfcache navigation
        window.addEventListener('pagehide', function() {
            if (!isPaused) {
                pause();
            }
        });

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                resume();
            }
        });

        // Start the timer with 1 second intervals
        render(remainingSeconds, false);
        sessionStorage.setItem(remainingKey, String(remainingSeconds));
        tickInterval = setInterval(tick, 1000);
    })();

    // Initialize counts on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCounts();
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Left arrow - previous question
            if (e.key === 'ArrowLeft' && currentQuestion > 0) {
                e.preventDefault();
                navigateQuestion(currentQuestion - 1);
            }
            // Right arrow - next question
            else if (e.key === 'ArrowRight' && currentQuestion < totalQuestions - 1) {
                e.preventDefault();
                navigateQuestion(currentQuestion + 1);
            }
            // F key - toggle flag
            else if (e.key === 'f' || e.key === 'F') {
                e.preventDefault();
                toggleFlag(currentQuestion);
            }
            // Number keys 1-4 for options A-D
            else if (e.key >= '1' && e.key <= '4') {
                e.preventDefault();
                const options = ['A', 'B', 'C', 'D'];
                const card = document.querySelector(`.question-card[data-question-index="${currentQuestion}"]`);
                if (card) {
                    const radio = card.querySelector(`input[value="${options[e.key - 1]}"]`);
                    if (radio) {
                        radio.checked = true;
                        updateAnswerStatus(currentQuestion);
                        const questionId = radio.name.match(/answers\[(\d+)\]/)?.[1];
                        if (questionId) {
                            saveAnswer(parseInt(questionId, 10), currentQuestion);
                        }
                    }
                }
            }
        });
    });

    // Prevent accidental page refresh
    window.addEventListener('beforeunload', function (e) {
        e.preventDefault();
        e.returnValue = '';
    });
</script>
@endpush
