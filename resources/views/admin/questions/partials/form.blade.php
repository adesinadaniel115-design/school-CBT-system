@php
    $editing = !empty($question);
@endphp

<div class="mb-4">
    <label for="subject_id" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
    <select id="subject_id" name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
        <option value="">-- Choose Subject --</option>
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}" {{ (string) old('subject_id', $editing ? $question->subject_id : '') === (string) $subject->id ? 'selected' : '' }}>
                {{ $subject->name }}
            </option>
        @endforeach
    </select>
    @error('subject_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="question_text" class="form-label fw-semibold">Question Text <span class="text-danger">*</span></label>
    <textarea id="question_text" 
              name="question_text" 
              rows="4" 
              class="form-control @error('question_text') is-invalid @enderror" 
              placeholder="Enter the question here..."
              required>{{ old('question_text', $editing ? $question->question_text : '') }}</textarea>
    @error('question_text')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="option_a" class="form-label fw-semibold">Option A <span class="text-danger">*</span></label>
        <input type="text" 
               id="option_a" 
               name="option_a" 
               class="form-control @error('option_a') is-invalid @enderror" 
               value="{{ old('option_a', $editing ? $question->option_a : '') }}" 
               placeholder="First option"
               required>
        @error('option_a')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="option_b" class="form-label fw-semibold">Option B <span class="text-danger">*</span></label>
        <input type="text" 
               id="option_b" 
               name="option_b" 
               class="form-control @error('option_b') is-invalid @enderror" 
               value="{{ old('option_b', $editing ? $question->option_b : '') }}" 
               placeholder="Second option"
               required>
        @error('option_b')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="option_c" class="form-label fw-semibold">Option C <span class="text-danger">*</span></label>
        <input type="text" 
               id="option_c" 
               name="option_c" 
               class="form-control @error('option_c') is-invalid @enderror" 
               value="{{ old('option_c', $editing ? $question->option_c : '') }}" 
               placeholder="Third option"
               required>
        @error('option_c')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="option_d" class="form-label fw-semibold">Option D <span class="text-danger">*</span></label>
        <input type="text" 
               id="option_d" 
               name="option_d" 
               class="form-control @error('option_d') is-invalid @enderror" 
               value="{{ old('option_d', $editing ? $question->option_d : '') }}" 
               placeholder="Fourth option"
               required>
        @error('option_d')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="correct_option" class="form-label fw-semibold">Correct Answer <span class="text-danger">*</span></label>
        <select id="correct_option" name="correct_option" class="form-select @error('correct_option') is-invalid @enderror" required>
            @foreach(['A', 'B', 'C', 'D'] as $option)
                <option value="{{ $option }}" {{ old('correct_option', $editing ? $question->correct_option : '') === $option ? 'selected' : '' }}>
                    Option {{ $option }}
                </option>
            @endforeach
        </select>
        @error('correct_option')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="difficulty_level" class="form-label fw-semibold">Difficulty Level <span class="text-danger">*</span></label>
        <select id="difficulty_level" name="difficulty_level" class="form-select @error('difficulty_level') is-invalid @enderror" required>
            @foreach(['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'] as $value => $label)
                <option value="{{ $value }}" {{ old('difficulty_level', $editing ? $question->difficulty_level : '') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('difficulty_level')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-4">
    <label for="explanation" class="form-label fw-semibold">Explanation <small class="text-muted">(Optional)</small></label>
    <textarea id="explanation" 
              name="explanation" 
              rows="3" 
              class="form-control @error('explanation') is-invalid @enderror" 
              placeholder="Provide an explanation or learning point for this question...">{{ old('explanation', $editing ? $question->explanation : '') }}</textarea>
    @error('explanation')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Help students understand why the answer is correct</small>
</div>
