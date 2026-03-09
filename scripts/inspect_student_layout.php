<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$html = view('layouts.student')->render();
// Show first 200 characters of the rendered output for any stray output before <!DOCTYPE>
$head = substr($html, 0, 200);
echo "--- FIRST 200 CHARS OF OUTPUT ---\n";
echo $head;
echo "\n--- END FIRST 200 CHARS ---\n\n";

$needle = 'Sidebar collapse';
$pos = strpos($html, $needle);
if ($pos === false) {
    echo "NOT FOUND\n";
    exit(0);
}

$start = max(0, $pos - 200);
$snippet = substr($html, $start, 800);

echo "--- SNIPPET AROUND '$needle' ---\n";
echo $snippet;
echo "\n--- END SNIPPET ---\n";

$styleStart = strpos($html, '<style>');
$styleEnd = strpos($html, '</style>', $styleStart);
if ($styleStart !== false && $styleEnd !== false) {
    echo "--- STYLE BLOCK (first <style>..</style>) ---\n";
    echo substr($html, $styleStart, $styleEnd - $styleStart + 8);
    echo "\n--- END STYLE BLOCK ---\n";
}
