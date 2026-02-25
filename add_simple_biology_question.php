<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ADDING SIMPLE BIOLOGY QUESTION ===\n\n";

// Get Biology subject
$biology = DB::table('subjects')
    ->where('name', 'LIKE', '%Biology%')
    ->first();

if (!$biology) {
    echo "Creating BIOLOGY subject...\n";
    $biologyId = DB::table('subjects')->insertGetId([
        'name' => 'BIOLOGY',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
} else {
    $biologyId = $biology->id;
    echo "Biology subject found with ID: {$biologyId}\n";
}

// Add simple biology question
$question = [
    'subject_id' => $biologyId,
    'question_text' => 'Which organelle is responsible for producing energy in a cell?',
    'option_a' => 'Nucleus',
    'option_b' => 'Mitochondrion',
    'option_c' => 'Ribosome',
    'option_d' => 'Chloroplast',
    'correct_option' => 'B',
    'explanation' => 'The mitochondrion is the powerhouse of the cell, responsible for producing ATP through cellular respiration.',
    'difficulty_level' => 'easy',
    'created_at' => now(),
    'updated_at' => now(),
];

$result = DB::table('questions')->insert($question);

if ($result) {
    echo "\n✅ Question added successfully!\n\n";
    echo "Question: Which organelle is responsible for producing energy in a cell?\n";
    echo "A) Nucleus\n";
    echo "B) Mitochondrion\n";
    echo "C) Ribosome\n";
    echo "D) Chloroplast\n\n";
    echo "Correct Answer: B\n";
    echo "Explanation: The mitochondrion is the powerhouse of the cell, responsible for producing ATP through cellular respiration.\n";
    
    $totalBiology = DB::table('questions')->where('subject_id', $biologyId)->count();
    echo "\nTotal Biology questions now: $totalBiology\n";
} else {
    echo "❌ Failed to add question\n";
}
