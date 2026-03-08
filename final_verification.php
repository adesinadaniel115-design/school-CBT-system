<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use App\Models\Question;

// Show all subjects with their counts
$subjects = Subject::all();
foreach($subjects as $s) {
    $count = Question::where('subject_id', $s->id)->count();
    echo $s->name . ": {$count} questions\n";
}

// Calculate totals
echo "\n---\n";
$total = Question::count();
echo "TOTAL QUESTIONS: {$total}\n";
echo "TOTAL SUBJECTS: " . $subjects->count() . "\n";
