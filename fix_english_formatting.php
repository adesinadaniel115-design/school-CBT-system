<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== FIXING ENGLISH QUESTION FORMATTING ===\n\n";

$updates = 0;
$fixes = [];

// FIX 1: Vocabulary questions - bold the target words
$vocabUpdates = [
    // Synonyms
    ["old" => "Choose the synonym for 'Candid':", "new" => "Choose the synonym for <strong>'Candid'</strong>:"],
    ["old" => "Choose the synonym for 'Meticulous':", "new" => "Choose the synonym for <strong>'Meticulous'</strong>:"],
    
    // Antonyms
    ["old" => "Choose the antonym for 'Amicable':", "new" => "Choose the antonym for <strong>'Amicable'</strong>:"],
    ["old" => "Choose the antonym for 'Obscure':", "new" => "Choose the antonym for <strong>'Obscure'</strong>:"],
    
    // Comprehension words
    ["old" => "The writer uses 'unprecedented' to show that growth is:", "new" => "The writer uses <strong>'unprecedented'</strong> to show that growth is:"],
    ["old" => "The phrase 'Silicon Lagoon' is a reference to:", "new" => "The phrase <strong>'Silicon Lagoon'</strong> is a reference to:"],
    ["old" => "A 'mirage' as used in the passage means:", "new" => "A <strong>'mirage'</strong> as used in the passage means:"],
    
    // Idioms
    ["old" => '"The news of the accident \'broke\' his heart." This is:', "new" => '"The news of the accident <strong>\'broke\'</strong> his heart." This is:'],
    ["old" => 'The idiom "To kick the bucket" means:', "new" => 'The idiom <strong>"To kick the bucket"</strong> means:'],
];

foreach ($vocabUpdates as $fix) {
    $affected = DB::table('questions')
        ->where('question_text', $fix['old'])
        ->update(['question_text' => $fix['new']]);
    
    if ($affected > 0) {
        $updates += $affected;
        $fixes[] = "✓ Fixed: " . substr($fix['old'], 0, 50) . "...";
        echo "✓ Updated $affected question(s): " . substr($fix['old'], 0, 50) . "...\n";
    }
}

// FIX 2: Oral forms - format phonetic symbols and examples
$oralUpdates = [
    ["old" => "Which word has the same vowel sound as /i:/ (e.g., 'Team')?", "new" => "Which word has the same vowel sound as <strong>/i:/</strong> (e.g., <em>'Team'</em>)?"],
    ["old" => "Identify the word with the /f/ sound:", "new" => "Identify the word with the <strong>/f/</strong> sound:"],
    ["old" => "Which word contains the /θ/ sound (e.g., 'Thin')?", "new" => "Which word contains the <strong>/θ/</strong> sound (e.g., <em>'Thin'</em>)?"],
    ["old" => "Select the word that rhymes with 'Bread':", "new" => "Select the word that rhymes with <strong>'Bread'</strong>:"],
    ["old" => "Which word has a silent 'k'?", "new" => "Which word has a silent <strong>'k'</strong>?"],
    ["old" => "Identify the word with the /tʃ/ sound (e.g., 'Church'):", "new" => "Identify the word with the <strong>/tʃ/</strong> sound (e.g., <em>'Church'</em>):"],
    ["old" => "Which word has the same stress pattern as 'E-DUC-ATE'?", "new" => "Which word has the same stress pattern as <strong>'E-DUC-ATE'</strong>?"],
    ["old" => "Pick the word that rhymes with 'Goat':", "new" => "Pick the word that rhymes with <strong>'Goat'</strong>:"],
    ["old" => "Which word contains the /z/ sound?", "new" => "Which word contains the <strong>/z/</strong> sound?"],
    ["old" => "Identify the word with the /v/ sound:", "new" => "Identify the word with the <strong>/v/</strong> sound:"],
];

foreach ($oralUpdates as $fix) {
    $affected = DB::table('questions')
        ->where('question_text', $fix['old'])
        ->update(['question_text' => $fix['new']]);
    
    if ($affected > 0) {
        $updates += $affected;
        $fixes[] = "✓ Fixed: " . substr($fix['old'], 0, 50) . "...";
        echo "✓ Updated $affected question(s): " . substr($fix['old'], 0, 50) . "...\n";
    }
}

// FIX 3: Cloze passage questions - make blanks clearer
$clozeUpdates = [
    ["old" => "The (26)____ was filed by the plaintiff.", "new" => "The <strong>(26)_______</strong> was filed by the plaintiff."],
    ["old" => "The suit was filed by the (27)____ who claimed his rights were violated.", "new" => "The suit was filed by the <strong>(27)_______</strong> who claimed his rights were violated."],
    ["old" => "The (28)____ argued that there was no admissible evidence.", "new" => "The <strong>(28)_______</strong> argued that there was no admissible evidence."],
    ["old" => "The witness was cross-examined by the (29)____.", "new" => "The witness was cross-examined by the <strong>(29)_______</strong>."],
    ["old" => "The suspect was reminded of his right to remain (30)____.", "new" => "The suspect was reminded of his right to remain <strong>(30)_______</strong>."],
    ["old" => "The judge ruled that the evidence was (31)____.", "new" => "The judge ruled that the evidence was <strong>(31)_______</strong>."],
    ["old" => "A (32)____ was issued for the arrest of the runaway thief.", "new" => "A <strong>(32)_______</strong> was issued for the arrest of the runaway thief."],
    ["old" => "The highest court in Nigeria is the (34)____ Court.", "new" => "The highest court in Nigeria is the <strong>(34)_______</strong> Court."],
    ["old" => "The jury's decision is called a (35)____.", "new" => "The jury's decision is called a <strong>(35)_______</strong>."],
];

foreach ($clozeUpdates as $fix) {
    $affected = DB::table('questions')
        ->where('question_text', $fix['old'])
        ->update(['question_text' => $fix['new']]);
    
    if ($affected > 0) {
        $updates += $affected;
        $fixes[] = "✓ Fixed: " . substr($fix['old'], 0, 50) . "...";
        echo "✓ Updated $affected question(s): " . substr($fix['old'], 0, 50) . "...\n";
    }
}

// FIX 4: Grammar questions - emphasize key parts
$grammarUpdates = [
    ["old" => "She is the ____ of the two sisters.", "new" => "She is the <strong>_______</strong> of the two sisters."],
    ["old" => "He is proficient ____ Mathematics.", "new" => "He is proficient <strong>_______</strong> Mathematics."],
    ["old" => "Each of the students ____ to bring a laptop tomorrow.", "new" => "Each of the students <strong>_______</strong> to bring a laptop tomorrow."],
    ["old" => "He didn't go to the party, ____?", "new" => "He didn't go to the party, <strong>_______</strong>?"],
];

foreach ($grammarUpdates as $fix) {
    $affected = DB::table('questions')
        ->where('question_text', $fix['old'])
        ->update(['question_text' => $fix['new']]);
    
    if ($affected > 0) {
        $updates += $affected;
        $fixes[] = "✓ Fixed: " . substr($fix['old'], 0, 50) . "...";
        echo "✓ Updated $affected question(s): " . substr($fix['old'], 0, 50) . "...\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total questions updated: $updates\n";
echo "Total fixes applied: " . count($fixes) . "\n\n";

echo "✅ All English questions formatted for clarity!\n\n";

echo "FORMATTING APPLIED:\n";
echo "• <strong> tags for key words and blanks\n";
echo "• <em> tags for examples in phonetics\n";
echo "• Bold phonetic symbols like /i:/ /θ/ /tʃ/\n";
echo "• Clearer blank indicators (______ with bold)\n";
