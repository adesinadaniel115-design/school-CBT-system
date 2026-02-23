<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Subject;
use App\Models\Question;

$subjects = [
    'Commerce' => [
        [
            'question_text' => 'Which document shows the list of goods sent to a buyer with quantities and prices?',
            'option_a' => 'Invoice',
            'option_b' => 'Receipt',
            'option_c' => 'Purchase order',
            'option_d' => 'Debit note',
            'correct_option' => 'A',
            'explanation' => 'An invoice is issued by the seller to show goods supplied, quantities, and prices.'
        ],
        [
            'question_text' => 'In international trade, the term FOB means the seller is responsible up to the point of?',
            'option_a' => 'Destination port',
            'option_b' => 'Loading goods on the ship',
            'option_c' => 'Unloading at destination',
            'option_d' => 'Final delivery to buyer',
            'correct_option' => 'B',
            'explanation' => 'FOB (Free On Board) means the seller delivers goods on board the ship at the port of shipment.'
        ],
        [
            'question_text' => 'Which source of capital is classified as internal financing?',
            'option_a' => 'Bank loan',
            'option_b' => 'Debentures',
            'option_c' => 'Retained earnings',
            'option_d' => 'Trade credit',
            'correct_option' => 'C',
            'explanation' => 'Retained earnings are funds generated within the business and are an internal source of finance.'
        ],
        [
            'question_text' => 'A retailer buys in small quantities and sells directly to?',
            'option_a' => 'Manufacturers',
            'option_b' => 'Wholesalers',
            'option_c' => 'Consumers',
            'option_d' => 'Importers',
            'correct_option' => 'C',
            'explanation' => 'Retailers sell goods in small quantities to final consumers.'
        ],
        [
            'question_text' => 'Which type of market is dominated by a few large firms?',
            'option_a' => 'Perfect competition',
            'option_b' => 'Oligopoly',
            'option_c' => 'Monopoly',
            'option_d' => 'Monopolistic competition',
            'correct_option' => 'B',
            'explanation' => 'An oligopoly is characterized by a small number of large firms controlling most of the market.'
        ],
    ],
    'Christian Religious Studies' => [
        [
            'question_text' => 'According to the Gospels, who baptized Jesus in the Jordan River?',
            'option_a' => 'Peter',
            'option_b' => 'John the Baptist',
            'option_c' => 'James',
            'option_d' => 'Andrew',
            'correct_option' => 'B',
            'explanation' => 'John the Baptist baptized Jesus in the Jordan River.'
        ],
        [
            'question_text' => 'The parable of the Good Samaritan teaches the importance of?',
            'option_a' => 'Forgiveness',
            'option_b' => 'Obedience to the law',
            'option_c' => 'Love and compassion for others',
            'option_d' => 'Fasting and prayer',
            'correct_option' => 'C',
            'explanation' => 'The Good Samaritan shows that we should show love and compassion to anyone in need.'
        ],
        [
            'question_text' => 'Which of the following is one of the Ten Commandments?',
            'option_a' => 'Turn the other cheek',
            'option_b' => 'Do not steal',
            'option_c' => 'Blessed are the meek',
            'option_d' => 'Love your enemies',
            'correct_option' => 'B',
            'explanation' => '"You shall not steal" is one of the Ten Commandments.'
        ],
        [
            'question_text' => 'In the Lord\'s Prayer, the phrase "Give us this day our daily bread" refers to?',
            'option_a' => 'Wealth and riches',
            'option_b' => 'Daily needs and provision',
            'option_c' => 'Festival offerings',
            'option_d' => 'Temple sacrifices',
            'correct_option' => 'B',
            'explanation' => 'It expresses dependence on God for daily needs and provision.'
        ],
        [
            'question_text' => 'Which event is celebrated by Christians as the resurrection of Jesus?',
            'option_a' => 'Christmas',
            'option_b' => 'Pentecost',
            'option_c' => 'Easter',
            'option_d' => 'Ascension',
            'correct_option' => 'C',
            'explanation' => 'Easter commemorates the resurrection of Jesus.'
        ],
    ],
];

$insertedSubjects = 0;
$insertedQuestions = 0;

foreach ($subjects as $subjectName => $questions) {
    $subject = Subject::firstOrCreate(['name' => $subjectName]);
    if ($subject->wasRecentlyCreated) {
        $insertedSubjects++;
    }

    foreach ($questions as $questionData) {
        $exists = Question::where('subject_id', $subject->id)
            ->where('question_text', $questionData['question_text'])
            ->exists();

        if ($exists) {
            continue;
        }

        Question::create(array_merge($questionData, [
            'subject_id' => $subject->id,
            'difficulty_level' => 'medium',
        ]));
        $insertedQuestions++;
    }
}

echo "Subjects added: {$insertedSubjects}\n";
echo "Questions added: {$insertedQuestions}\n";
