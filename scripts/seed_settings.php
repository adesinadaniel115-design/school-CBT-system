<?php

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

$keys = [
    'school_questions_count',
    'school_duration_minutes',
    'jamb_questions_per_subject',
    'jamb_english_questions',
    'jamb_duration_minutes',
    'allow_question_flagging',
    'show_results_immediately',
    'allow_exam_review',
    'shuffle_questions',
    'shuffle_options',
];

foreach ($keys as $k) {
    if (Cache::has($k)) {
        Setting::setValue($k, Cache::get($k));
        echo "set: {$k}\n";
    } else {
        echo "skipped: {$k}\n";
    }
}

echo "Done.\n";
