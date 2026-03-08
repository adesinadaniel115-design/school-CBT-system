<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use Illuminate\Support\Facades\Cache;

$schoolQuestionsCount = Cache::get('school_questions_count', 40);
$all = Subject::withCount('questions')->orderBy('name')->get();
$total = $all->count();
$eligible = $all->filter(fn($s) => $s->questions_count >= $schoolQuestionsCount)->count();

echo "total={$total}, eligible={$eligible}, threshold={$schoolQuestionsCount}\n";

echo "subjects:\n";
foreach ($all as $s) {
    echo "- {$s->name} (questions: {$s->questions_count})\n";
}
