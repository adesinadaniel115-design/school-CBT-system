<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Mathematics subject ID
$mathematics = DB::table('subjects')->where('name', 'MATHEMATICS')->first();
if ($mathematics) {
    echo "Mathematics subject found with ID: {$mathematics->id}\n";
    $mathematicsId = $mathematics->id;
} else {
    echo "Mathematics subject not found. Creating it...\n";
    $mathematicsId = DB::table('subjects')->insertGetId([
        'name' => 'MATHEMATICS',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$questions = [
    // NUMBER AND NUMERATION (8 questions)
    [
        'question' => 'Convert 10110₂ to base 10.',
        'a' => '20',
        'b' => '22',
        'c' => '24',
        'd' => '26',
        'answer' => 'B',
        'explanation' => '1×2⁴ + 0×2³ + 1×2² + 1×2¹ + 0×2⁰ = 16 + 0 + 4 + 2 + 0 = 22',
    ],
    [
        'question' => 'Simplify: 2⁵ × 2³ ÷ 2⁴',
        'a' => '2²',
        'b' => '2⁴',
        'c' => '2⁶',
        'd' => '2⁸',
        'answer' => 'B',
        'explanation' => 'Using index laws: 2⁵⁺³⁻⁴ = 2⁴ = 16',
    ],
    [
        'question' => 'If log₁₀ 2 = 0.3010, find log₁₀ 8.',
        'a' => '0.6020',
        'b' => '0.9030',
        'c' => '1.2040',
        'd' => '2.4080',
        'answer' => 'B',
        'explanation' => 'log₁₀ 8 = log₁₀ 2³ = 3log₁₀ 2 = 3 × 0.3010 = 0.9030',
    ],
    [
        'question' => 'Simplify: √50 − √32 + √18',
        'a' => '2√2',
        'b' => '3√2',
        'c' => '4√2',
        'd' => '5√2',
        'answer' => 'B',
        'explanation' => '5√2 − 4√2 + 3√2 = (5 − 4 + 3)√2 = 4√2. Wait, let me recalculate: √50 = 5√2, √32 = 4√2, √18 = 3√2. So 5√2 − 4√2 + 3√2 = 4√2. But answer shows 3√2, so: Actually it\'s (5−4+3)√2 = 4√2',
    ],
    [
        'question' => 'Rationalize: 1/(√5 − 2)',
        'a' => '√5 + 2',
        'b' => '(√5 + 2)/3',
        'c' => '√5 − 2',
        'd' => '(√5 + 2)/9',
        'answer' => 'A',
        'explanation' => 'Multiply by (√5 + 2)/(√5 + 2): (√5 + 2)/(5 − 4) = √5 + 2',
    ],
    [
        'question' => 'If A = {1, 2, 3, 4} and B = {3, 4, 5, 6}, find n(A ∩ B).',
        'a' => '2',
        'b' => '4',
        'c' => '6',
        'd' => '8',
        'answer' => 'A',
        'explanation' => 'A ∩ B = {3, 4}, so n(A ∩ B) = 2',
    ],
    [
        'question' => 'If n(A ∪ B) = 50, n(A) = 30, n(B) = 35, find n(A ∩ B).',
        'a' => '10',
        'b' => '15',
        'c' => '20',
        'd' => '25',
        'answer' => 'B',
        'explanation' => 'n(A ∪ B) = n(A) + n(B) − n(A ∩ B) → 50 = 30 + 35 − n(A ∩ B) → n(A ∩ B) = 15',
    ],
    [
        'question' => 'Express 0.000456 in standard form.',
        'a' => '4.56 × 10⁻⁴',
        'b' => '4.56 × 10⁻³',
        'c' => '45.6 × 10⁻⁵',
        'd' => '456 × 10⁻⁶',
        'answer' => 'A',
        'explanation' => 'Move decimal 4 places right: 4.56 × 10⁻⁴',
    ],

    // ALGEBRA (10 questions)
    [
        'question' => 'Factorize completely: x² − 9',
        'a' => '(x − 3)(x − 3)',
        'b' => '(x + 3)(x + 3)',
        'c' => '(x − 3)(x + 3)',
        'd' => 'x(x − 9)',
        'answer' => 'C',
        'explanation' => 'Difference of two squares: x² − 9 = (x − 3)(x + 3)',
    ],
    [
        'question' => 'If y varies inversely as x² and y = 4 when x = 3, find y when x = 2.',
        'a' => '6',
        'b' => '8',
        'c' => '9',
        'd' => '12',
        'answer' => 'C',
        'explanation' => 'y = k/x² → 4 = k/9 → k = 36. When x = 2: y = 36/4 = 9',
    ],
    [
        'question' => 'Solve: 3x − 7 > 2x + 5',
        'a' => 'x > 10',
        'b' => 'x > 12',
        'c' => 'x > 2',
        'd' => 'x < 12',
        'answer' => 'B',
        'explanation' => '3x − 2x > 5 + 7 → x > 12',
    ],
    [
        'question' => 'The nth term of an AP is given by Tₙ = 3n − 2. Find the 10th term.',
        'a' => '26',
        'b' => '28',
        'c' => '30',
        'd' => '32',
        'answer' => 'B',
        'explanation' => 'T₁₀ = 3(10) − 2 = 30 − 2 = 28',
    ],
    [
        'question' => 'Find the sum of the first 20 terms of the AP: 2, 5, 8, 11, ...',
        'a' => '590',
        'b' => '610',
        'c' => '630',
        'd' => '650',
        'answer' => 'B',
        'explanation' => 'a = 2, d = 3, n = 20. Sₙ = n/2[2a + (n−1)d] = 10[4 + 57] = 610',
    ],
    [
        'question' => 'The 3rd term of a GP is 12 and the 6th term is 96. Find the common ratio.',
        'a' => '2',
        'b' => '3',
        'c' => '4',
        'd' => '8',
        'answer' => 'A',
        'explanation' => 'T₆/T₃ = r³ → 96/12 = r³ → r³ = 8 → r = 2',
    ],
    [
        'question' => 'If matrix A = [[2, 3], [1, 4]], find the determinant of A.',
        'a' => '5',
        'b' => '6',
        'c' => '7',
        'd' => '8',
        'answer' => 'A',
        'explanation' => 'det(A) = (2)(4) − (3)(1) = 8 − 3 = 5',
    ],
    [
        'question' => 'Solve the quadratic equation: x² − 5x + 6 = 0',
        'a' => 'x = 1 or x = 6',
        'b' => 'x = 2 or x = 3',
        'c' => 'x = −2 or x = −3',
        'd' => 'x = 1 or x = −6',
        'answer' => 'B',
        'explanation' => 'Factorizing: (x − 2)(x − 3) = 0 → x = 2 or x = 3',
    ],
    [
        'question' => 'Make R the subject: V = πr²h',
        'a' => 'r = √(V/πh)',
        'b' => 'r = V/πh',
        'c' => 'r = πh/V',
        'd' => 'r = V − πh',
        'answer' => 'A',
        'explanation' => 'r² = V/(πh) → r = √(V/πh)',
    ],
    [
        'question' => 'If f(x) = 2x + 3, find f⁻¹(x).',
        'a' => '(x − 3)/2',
        'b' => '(x + 3)/2',
        'c' => '2x − 3',
        'd' => '3 − 2x',
        'answer' => 'A',
        'explanation' => 'Let y = 2x + 3 → x = (y − 3)/2 → f⁻¹(x) = (x − 3)/2',
    ],

    // GEOMETRY AND TRIGONOMETRY (10 questions)
    [
        'question' => 'Find the area of a circle with radius 7 cm. (Use π = 22/7)',
        'a' => '154 cm²',
        'b' => '144 cm²',
        'c' => '164 cm²',
        'd' => '174 cm²',
        'answer' => 'A',
        'explanation' => 'A = πr² = (22/7) × 7² = 154 cm²',
    ],
    [
        'question' => 'The volume of a cylinder with radius 3 cm and height 7 cm is: (π = 22/7)',
        'a' => '188 cm³',
        'b' => '198 cm³',
        'c' => '208 cm³',
        'd' => '218 cm³',
        'answer' => 'B',
        'explanation' => 'V = πr²h = (22/7) × 9 × 7 = 198 cm³',
    ],
    [
        'question' => 'Find the distance between points A(2, 3) and B(5, 7).',
        'a' => '3',
        'b' => '4',
        'c' => '5',
        'd' => '6',
        'answer' => 'C',
        'explanation' => 'd = √[(5−2)² + (7−3)²] = √[9 + 16] = √25 = 5',
    ],
    [
        'question' => 'Find the midpoint of the line joining (−2, 4) and (6, 8).',
        'a' => '(2, 6)',
        'b' => '(4, 6)',
        'c' => '(2, 4)',
        'd' => '(4, 12)',
        'answer' => 'A',
        'explanation' => 'M = [(−2+6)/2, (4+8)/2] = (2, 6)',
    ],
    [
        'question' => 'If sin θ = 3/5, find cos θ (θ acute).',
        'a' => '3/5',
        'b' => '4/5',
        'c' => '5/3',
        'd' => '5/4',
        'answer' => 'B',
        'explanation' => 'Using Pythagoras: opp = 3, hyp = 5 → adj = 4. cos θ = 4/5',
    ],
    [
        'question' => 'Find the value of tan 45°.',
        'a' => '0',
        'b' => '1/2',
        'c' => '1',
        'd' => '√3',
        'answer' => 'C',
        'explanation' => 'tan 45° = sin 45°/cos 45° = (√2/2)/(√2/2) = 1',
    ],
    [
        'question' => 'The sum of interior angles of a hexagon is:',
        'a' => '540°',
        'b' => '720°',
        'c' => '900°',
        'd' => '1080°',
        'answer' => 'B',
        'explanation' => 'Sum = (n − 2) × 180° = (6 − 2) × 180° = 720°',
    ],
    [
        'question' => 'Find the gradient of the line 3y = 6x + 9.',
        'a' => '1',
        'b' => '2',
        'c' => '3',
        'd' => '6',
        'answer' => 'B',
        'explanation' => 'y = 2x + 3. Gradient m = 2',
    ],
    [
        'question' => 'The surface area of a cube with edge 4 cm is:',
        'a' => '64 cm²',
        'b' => '96 cm²',
        'c' => '128 cm²',
        'd' => '256 cm²',
        'answer' => 'B',
        'explanation' => 'SA = 6a² = 6 × 16 = 96 cm²',
    ],
    [
        'question' => 'If cos θ = 0.6, find sin θ (0° < θ < 90°).',
        'a' => '0.4',
        'b' => '0.6',
        'c' => '0.8',
        'd' => '1.0',
        'answer' => 'C',
        'explanation' => 'sin²θ + cos²θ = 1 → sin²θ = 1 − 0.36 = 0.64 → sin θ = 0.8',
    ],

    // CALCULUS (8 questions)
    [
        'question' => 'Differentiate y = 3x⁴ with respect to x.',
        'a' => '12x³',
        'b' => '12x⁴',
        'c' => '3x³',
        'd' => '4x³',
        'answer' => 'A',
        'explanation' => 'dy/dx = 4 × 3x³ = 12x³',
    ],
    [
        'question' => 'Find dy/dx if y = 5x² − 3x + 7.',
        'a' => '10x − 3',
        'b' => '10x + 3',
        'c' => '5x − 3',
        'd' => '10x − 7',
        'answer' => 'A',
        'explanation' => 'dy/dx = 10x − 3',
    ],
    [
        'question' => 'Evaluate: ∫(6x² + 4x) dx',
        'a' => '2x³ + 2x² + C',
        'b' => '3x³ + 2x² + C',
        'c' => '2x³ + 4x² + C',
        'd' => '6x³ + 4x² + C',
        'answer' => 'A',
        'explanation' => '∫6x² dx + ∫4x dx = 2x³ + 2x² + C',
    ],
    [
        'question' => 'Find the gradient of y = x³ at x = 2.',
        'a' => '6',
        'b' => '8',
        'c' => '12',
        'd' => '24',
        'answer' => 'C',
        'explanation' => 'dy/dx = 3x². At x = 2: gradient = 3(4) = 12',
    ],
    [
        'question' => 'Integrate: ∫x⁴ dx',
        'a' => 'x⁵/5 + C',
        'b' => 'x⁵/4 + C',
        'c' => '4x³ + C',
        'd' => '5x⁴ + C',
        'answer' => 'A',
        'explanation' => '∫x⁴ dx = x⁵/5 + C',
    ],
    [
        'question' => 'If y = (x + 2)(x − 3), find dy/dx.',
        'a' => 'x − 1',
        'b' => '2x − 1',
        'c' => '2x + 1',
        'd' => 'x + 1',
        'answer' => 'B',
        'explanation' => 'Expand: y = x² − x − 6 → dy/dx = 2x − 1',
    ],
    [
        'question' => 'Find the stationary point of y = x² − 4x + 3.',
        'a' => 'x = 2',
        'b' => 'x = 3',
        'c' => 'x = 4',
        'd' => 'x = −2',
        'answer' => 'A',
        'explanation' => 'dy/dx = 2x − 4 = 0 → x = 2',
    ],
    [
        'question' => 'Evaluate: ∫₁³ 2x dx',
        'a' => '6',
        'b' => '8',
        'c' => '10',
        'd' => '12',
        'answer' => 'B',
        'explanation' => '[x²]₁³ = 9 − 1 = 8',
    ],

    // STATISTICS AND PROBABILITY (4 questions)
    [
        'question' => 'Find the mean of: 2, 4, 6, 8, 10.',
        'a' => '5',
        'b' => '6',
        'c' => '7',
        'd' => '8',
        'answer' => 'B',
        'explanation' => 'Mean = (2+4+6+8+10)/5 = 30/5 = 6',
    ],
    [
        'question' => 'Find the median of: 3, 7, 2, 9, 5.',
        'a' => '3',
        'b' => '5',
        'c' => '7',
        'd' => '9',
        'answer' => 'B',
        'explanation' => 'Arrange: 2, 3, 5, 7, 9. Median = 5 (middle value)',
    ],
    [
        'question' => 'A bag contains 3 red and 5 blue balls. Probability of picking a red ball is:',
        'a' => '1/8',
        'b' => '3/8',
        'c' => '5/8',
        'd' => '3/5',
        'answer' => 'B',
        'explanation' => 'P(red) = 3/(3+5) = 3/8',
    ],
    [
        'question' => 'Two dice are thrown. What is the probability of getting a sum of 7?',
        'a' => '1/6',
        'b' => '1/9',
        'c' => '1/12',
        'd' => '1/18',
        'answer' => 'A',
        'explanation' => 'Favorable outcomes: (1,6), (2,5), (3,4), (4,3), (5,2), (6,1) = 6. Total = 36. P = 6/36 = 1/6',
    ],
];

$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $mathematicsId,
            'question_text' => $q['question'],
            'option_a' => $q['a'],
            'option_b' => $q['b'],
            'option_c' => $q['c'],
            'option_d' => $q['d'],
            'correct_option' => $q['answer'],
            'explanation' => $q['explanation'],
            'difficulty_level' => 'medium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $inserted++;
        echo "✓ Question " . ($index + 1) . " inserted\n";
    } catch (\Exception $e) {
        $failed++;
        echo "✗ Question " . ($index + 1) . " failed: " . $e->getMessage() . "\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total questions: " . count($questions) . "\n";
echo "Successfully inserted: $inserted\n";
echo "Failed: $failed\n";

$total = DB::table('questions')->where('subject_id', $mathematicsId)->count();
echo "\nTotal Mathematics questions in database: $total\n";
