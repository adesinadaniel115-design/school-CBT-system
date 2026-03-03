<?php

$projectRoot = dirname(__FILE__);
require $projectRoot . '/vendor/autoload.php';

$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use App\Models\Question;

$subjects = Subject::all();
foreach ($subjects as $subject) {
    $count = Question::where('subject_id', $subject->id)->count();
    echo $subject->name . ': ' . $count . " questions\n";
}
