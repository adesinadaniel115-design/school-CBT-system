<?php
require 'bootstrap/app.php';
use App\Models\Question;
use App\Models\Subject;

$economics = Subject::where('name', 'ECONOMICS')->first();
if ($economics) {
    $count = Question::where('subject_id', $economics->id)->count();
    echo "ECONOMICS: {$count} questions\n";
} else {
    echo "ECONOMICS subject not found\n";
}

// Show all subjects for comparison
echo "\nAll subjects:\n";
$all = Subject::all();
foreach($all as $s) {
    $count = Question::where('subject_id', $s->id)->count();
    echo $s->name . ": {$count} questions\n";
}
