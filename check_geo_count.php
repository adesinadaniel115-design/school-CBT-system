<?php

$projectRoot = dirname(__FILE__);
require $projectRoot . '/vendor/autoload.php';

$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use App\Models\Question;

$subject = Subject::where('name', 'Geography')->first();
if ($subject) {
    $count = Question::where('subject_id', $subject->id)->count();
    echo "Geography questions: {$count}\n";
} else {
    echo "Geography subject not found\n";
}
