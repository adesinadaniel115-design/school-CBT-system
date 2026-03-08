<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Subject;

$englishSubject = Subject::where('name', 'LIKE', '%English%')
    ->orWhere('name', 'LIKE', '%ENGLISH%')
    ->first();

if ($englishSubject) {
    echo "English subject found: ID {$englishSubject->id}, name: {$englishSubject->name}\n";
    $count = \App\Models\Question::where('subject_id', $englishSubject->id)->count();
    echo "Total questions in English subject: $count\n";
} else {
    echo "English subject not found\n";
}
