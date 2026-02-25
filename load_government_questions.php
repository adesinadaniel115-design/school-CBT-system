<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Government subject ID
$government = DB::table('subjects')->where('name', 'GOVERNMENT')->first();
if ($government) {
    echo "Government subject found with ID: {$government->id}\n";
    $governmentId = $government->id;
} else {
    echo "Government subject not found. Creating it...\n";
    $governmentId = DB::table('subjects')->insertGetId([
        'name' => 'GOVERNMENT',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$questions = [
    [
        'question' => 'Which of the following best explains sovereignty?',
        'a' => 'Ability of a group to influence others',
        'b' => 'Supreme power of a state to govern itself',
        'c' => 'Economic independence from other countries',
        'd' => 'Citizens\' right to vote',
        'answer' => 'B',
        'explanation' => 'Sovereignty refers to supreme political authority.',
    ],
    [
        'question' => 'When no single political party wins an election and parties join to govern, it is:',
        'a' => 'Monarchy',
        'b' => 'One‑party state',
        'c' => 'Coalition government',
        'd' => 'Federal government',
        'answer' => 'C',
        'explanation' => 'A coalition is government by multiple parties.',
    ],
    [
        'question' => 'Which arm of government makes laws?',
        'a' => 'Executive',
        'b' => 'Legislature',
        'c' => 'Judiciary',
        'd' => 'Civil Service',
        'answer' => 'B',
        'explanation' => 'The legislature is the law‑making arm.',
    ],
    [
        'question' => 'A constitution that can be amended by a simple majority in parliament is:',
        'a' => 'Rigid',
        'b' => 'Flexible',
        'c' => 'Unwritten',
        'd' => 'Absolute',
        'answer' => 'B',
        'explanation' => 'Flexible constitutions allow easy change.',
    ],
    [
        'question' => 'The doctrine of separation of powers ensures that:',
        'a' => 'One branch dominates government',
        'b' => 'Civil service controls legislature',
        'c' => 'Each branch checks the others',
        'd' => 'Only judiciary holds power',
        'answer' => 'C',
        'explanation' => 'Separation prevents concentration of power.',
    ],
    [
        'question' => 'Which of the following is NOT a political ideology?',
        'a' => 'Capitalism',
        'b' => 'Socialism',
        'c' => 'Communism',
        'd' => 'Federalism',
        'answer' => 'D',
        'explanation' => 'Federalism is a system of government, not an ideology.',
    ],
    [
        'question' => 'A major weakness of a one‑party system is that it:',
        'a' => 'Encourages political debate',
        'b' => 'Reduces political pluralism',
        'c' => 'Promotes consensus',
        'd' => 'Creates multiple power centers',
        'answer' => 'B',
        'explanation' => 'One-party systems restrict political competition.',
    ],
    [
        'question' => 'Fundamental human rights in Nigeria are protected by:',
        'a' => 'Common Law',
        'b' => 'The Constitution',
        'c' => 'Customary Law',
        'd' => 'Electoral Act',
        'answer' => 'B',
        'explanation' => 'Rights are enshrined in the constitution.',
    ],
    [
        'question' => 'Citizenship by birth means:',
        'a' => 'Naturalization',
        'b' => 'Born within the country\'s territory',
        'c' => 'Marriage to a citizen',
        'd' => 'Residence for many years',
        'answer' => 'B',
        'explanation' => 'Birth in the country confers citizenship.',
    ],
    [
        'question' => 'The principle of the rule of law means:',
        'a' => 'Law applies to citizens only',
        'b' => 'Leaders are above the law',
        'c' => 'Everyone is subject to the law',
        'd' => 'Laws are not enforced',
        'answer' => 'C',
        'explanation' => 'Rule of law means no one is above the law.',
    ],
    [
        'question' => 'Which structure of governance divides power between central and regional governments?',
        'a' => 'Unitary',
        'b' => 'Federal',
        'c' => 'Confederal',
        'd' => 'Military',
        'answer' => 'B',
        'explanation' => 'Federal system splits authority between levels.',
    ],
    [
        'question' => 'In a presidential system, the executive is:',
        'a' => 'Part of the legislature',
        'b' => 'Appointed by judiciary',
        'c' => 'Separately elected',
        'd' => 'Controlled by legislature',
        'answer' => 'C',
        'explanation' => 'President is elected independently.',
    ],
    [
        'question' => 'A one‑party system is characterized by:',
        'a' => 'Multiple competitive parties',
        'b' => 'Single legal political party',
        'c' => 'Coalition government',
        'd' => 'Frequent party mergers',
        'answer' => 'B',
        'explanation' => 'Only one party controls governance.',
    ],
    [
        'question' => 'A pressure group mainly aims to:',
        'a' => 'Form government',
        'b' => 'Influence public policy',
        'c' => 'Interpret laws',
        'd' => 'Organize elections',
        'answer' => 'B',
        'explanation' => 'Pressure groups lobby for specific interests.',
    ],
    [
        'question' => 'What is the first step in making law?',
        'a' => 'Judicial review',
        'b' => 'Passing a bill in legislature',
        'c' => 'Public referendum',
        'd' => 'Presidential assent',
        'answer' => 'B',
        'explanation' => 'Bills must pass through legislature first.',
    ],
    [
        'question' => 'Free and fair elections promote:',
        'a' => 'Authoritarian rule',
        'b' => 'Citizen participation',
        'c' => 'Pressure group dominance',
        'd' => 'Political secrecy',
        'answer' => 'B',
        'explanation' => 'Such elections encourage democratic participation.',
    ],
    [
        'question' => 'The Civil Service is primarily responsible for:',
        'a' => 'Making laws',
        'b' => 'Implementing government policies',
        'c' => 'Conducting elections',
        'd' => 'Drafting the constitution',
        'answer' => 'B',
        'explanation' => 'Civil servants carry out government functions.',
    ],
    [
        'question' => 'Absolute monarchy gives power to:',
        'a' => 'Elected officials',
        'b' => 'Citizens',
        'c' => 'One ruler',
        'd' => 'Legislature',
        'answer' => 'C',
        'explanation' => 'Absolute monarchs hold all authority.',
    ],
    [
        'question' => 'Judicial independence ensures:',
        'a' => 'Courts are influenced by politics',
        'b' => 'Fair interpretation of law',
        'c' => 'Legislature controls judiciary',
        'd' => 'Executive appointment of judges',
        'answer' => 'B',
        'explanation' => 'Independence ensures unbiased judgment.',
    ],
    [
        'question' => 'A recall system allows citizens to:',
        'a' => 'Elect judges',
        'b' => 'Remove an elected representative early',
        'c' => 'Appoint ministers',
        'd' => 'Amend constitution',
        'answer' => 'B',
        'explanation' => 'Recall removes officials for poor performance.',
    ],
    [
        'question' => 'Pressure groups differ from political parties because they:',
        'a' => 'Contest elections',
        'b' => 'Influence policy without running for office',
        'c' => 'Draft constitutions',
        'd' => 'Control judiciary',
        'answer' => 'B',
        'explanation' => 'They lobby rather than seek office.',
    ],
    [
        'question' => 'Which ideology supports collective ownership of resources?',
        'a' => 'Democracy',
        'b' => 'Capitalism',
        'c' => 'Socialism',
        'd' => 'Federalism',
        'answer' => 'C',
        'explanation' => 'Socialism emphasizes public ownership.',
    ],
    [
        'question' => 'Supremacy of the constitution means:',
        'a' => 'Constitution is flexible only',
        'b' => 'All laws must conform to it',
        'c' => 'Judiciary ignores constitution',
        'd' => 'Constitution cannot be amended',
        'answer' => 'B',
        'explanation' => 'Constitution overrides other laws.',
    ],
    [
        'question' => 'A feature of democratic government is:',
        'a' => 'Suppression of dissent',
        'b' => 'Respect for human rights',
        'c' => 'One‑party dominance',
        'd' => 'Authoritarian rule',
        'answer' => 'B',
        'explanation' => 'Democracies uphold rights.',
    ],
    [
        'question' => 'Civil liberties refer to:',
        'a' => 'Citizens\' freedoms guaranteed by law',
        'b' => 'Government restrictions on trade',
        'c' => 'Armed forces\' authority',
        'd' => 'Taxation policies',
        'answer' => 'A',
        'explanation' => 'Civil liberties protect freedoms.',
    ],
    [
        'question' => 'Foreign policy deals with:',
        'a' => 'Domestic government',
        'b' => 'International relations',
        'c' => 'Local government structure',
        'd' => 'Judicial appointments',
        'answer' => 'B',
        'explanation' => 'It guides external engagements.',
    ],
    [
        'question' => 'Which organization promotes unity among African nations?',
        'a' => 'UN',
        'b' => 'AU',
        'c' => 'ASEAN',
        'd' => 'NATO',
        'answer' => 'B',
        'explanation' => 'African Union focuses on continental cooperation.',
    ],
    [
        'question' => 'Political socialization refers to:',
        'a' => 'Election campaigning',
        'b' => 'Learning political culture and values',
        'c' => 'Political violence',
        'd' => 'Drafting legislation',
        'answer' => 'B',
        'explanation' => 'It shapes beliefs about politics.',
    ],
    [
        'question' => 'A democratic government is characterized by:',
        'a' => 'Election of leaders',
        'b' => 'Military dictatorship',
        'c' => 'One-party dominance',
        'd' => 'One‑party rule',
        'answer' => 'A',
        'explanation' => 'Elections allow public choice.',
    ],
    [
        'question' => 'Federalism is best described as:',
        'a' => 'Centralization of power',
        'b' => 'Sharing of power between levels',
        'c' => 'Power exclusive to local government',
        'd' => 'Military rule',
        'answer' => 'B',
        'explanation' => 'Federalism splits authority.',
    ],
    [
        'question' => 'A republic differs from a monarchy because:',
        'a' => 'Leader is elected',
        'b' => 'Leader inherits position',
        'c' => 'There is no judiciary',
        'd' => 'Legislature controls courts',
        'answer' => 'A',
        'explanation' => 'Republic leaders are chosen.',
    ],
    [
        'question' => 'Judicial independence is weakened by:',
        'a' => 'Merit appointments',
        'b' => 'Political interference',
        'c' => 'Constitutional safeguards',
        'd' => 'Tenure protection',
        'answer' => 'B',
        'explanation' => 'Politics undermines impartiality.',
    ],
    [
        'question' => 'A bicameral legislature means:',
        'a' => 'One chamber',
        'b' => 'Two chambers',
        'c' => 'Legislature + executive',
        'd' => 'Three branches',
        'answer' => 'B',
        'explanation' => 'Two chambers enhance checks.',
    ],
    [
        'question' => 'Legislative oversight refers to:',
        'a' => 'Legislature making law alone',
        'b' => 'Legislature monitoring executive',
        'c' => 'Legislature controlling judiciary',
        'd' => 'Legislature drafting foreign policy',
        'answer' => 'B',
        'explanation' => 'Oversight ensures accountability.',
    ],
    [
        'question' => 'The electorate refers to:',
        'a' => 'Government officials',
        'b' => 'Eligible voters',
        'c' => 'Judges',
        'd' => 'Civil servants',
        'answer' => 'B',
        'explanation' => 'Electorate are people who vote.',
    ],
    [
        'question' => 'A pluralist political system means:',
        'a' => 'Multiple political voices coexist',
        'b' => 'One ideology dominates',
        'c' => 'Military controls government',
        'd' => 'Civil liberties are limited',
        'answer' => 'A',
        'explanation' => 'Pluralism accommodates diversity.',
    ],
    [
        'question' => 'Which is the highest law in Nigeria?',
        'a' => 'Statutes',
        'b' => 'Customary law',
        'c' => 'Constitution',
        'd' => 'Judicial precedent',
        'answer' => 'C',
        'explanation' => 'Constitution is supreme.',
    ],
    [
        'question' => 'Which electoral system emphasizes majority rule?',
        'a' => 'Proportional representation',
        'b' => 'First‑past‑the‑post',
        'c' => 'Indirect election',
        'd' => 'Uncontested elections',
        'answer' => 'B',
        'explanation' => 'First‑past‑the‑post awards winner most votes.',
    ],
];

$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $governmentId,
            'question_text' => $q['question'],
            'option_a' => $q['a'],
            'option_b' => $q['b'],
            'option_c' => $q['c'],
            'option_d' => $q['d'],
            'correct_option' => $q['answer'],
            'explanation' => $q['explanation'],
            'difficulty_level' => 'medium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $inserted++;
        echo "✓ Question " . ($index + 1) . " inserted\n";
    } catch (\Exception $e) {
        $failed++;
        echo "✗ Question " . ($index + 1) . " failed: " . $e->getMessage() . "\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total questions: " . count($questions) . "\n";
echo "Successfully inserted: $inserted\n";
echo "Failed: $failed\n";

// Show total questions in Government
$total = DB::table('questions')->where('subject_id', $governmentId)->count();
echo "\nTotal Government questions in database: $total\n";
