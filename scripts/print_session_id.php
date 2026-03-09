<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ExamSession;

$session = ExamSession::orderBy('id', 'desc')->first();
if (!$session) {
    echo "No exam sessions found\n";
    exit(0);
}

echo "Latest session id: {$session->id}\n";
