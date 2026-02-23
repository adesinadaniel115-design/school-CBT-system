<?php
require 'bootstrap/app.php';
$app->make('request');

$count = \App\Models\Question::count();
echo "Current dummy questions: " . $count . "\n";

if ($count > 0) {
    \App\Models\Question::truncate();
    echo "✅ All dummy questions DELETED!\n";
    echo "✅ Database is now CLEAN - ready for new questions\n";
} else {
    echo "⚠️ No questions found\n";
}

$newCount = \App\Models\Question::count();
echo "Questions remaining: " . $newCount . "\n";
