<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Subject;
use App\Models\Question;

$questionBank = [
    'BIOLOGY' => [
        [
            'question_text' => 'Which organelle is responsible for cellular respiration and ATP production?',
            'option_a' => 'Nucleus',
            'option_b' => 'Mitochondrion',
            'option_c' => 'Ribosome',
            'option_d' => 'Golgi apparatus',
            'correct_option' => 'B',
            'explanation' => 'Mitochondria are the sites of aerobic respiration and ATP synthesis.'
        ],
        [
            'question_text' => 'The movement of water from a region of high water potential to low water potential across a membrane is called?',
            'option_a' => 'Diffusion',
            'option_b' => 'Osmosis',
            'option_c' => 'Active transport',
            'option_d' => 'Translocation',
            'correct_option' => 'B',
            'explanation' => 'Osmosis is the diffusion of water across a selectively permeable membrane.'
        ],
        [
            'question_text' => 'Which blood cells are primarily responsible for clotting?',
            'option_a' => 'Red blood cells',
            'option_b' => 'White blood cells',
            'option_c' => 'Platelets',
            'option_d' => 'Plasma cells',
            'correct_option' => 'C',
            'explanation' => 'Platelets (thrombocytes) initiate blood clotting.'
        ],
        [
            'question_text' => 'The basic structural and functional unit of the kidney is the?',
            'option_a' => 'Neuron',
            'option_b' => 'Nephron',
            'option_c' => 'Alveolus',
            'option_d' => 'Axon',
            'correct_option' => 'B',
            'explanation' => 'Each nephron filters blood and forms urine.'
        ],
        [
            'question_text' => 'Which process converts glucose into energy without oxygen?',
            'option_a' => 'Aerobic respiration',
            'option_b' => 'Photosynthesis',
            'option_c' => 'Anaerobic respiration',
            'option_d' => 'Transpiration',
            'correct_option' => 'C',
            'explanation' => 'Anaerobic respiration releases energy from glucose in the absence of oxygen.'
        ],
    ],
    'CHEMISTRY' => [
        [
            'question_text' => 'Which gas is produced when a metal reacts with a dilute acid?',
            'option_a' => 'Carbon dioxide',
            'option_b' => 'Hydrogen',
            'option_c' => 'Oxygen',
            'option_d' => 'Nitrogen',
            'correct_option' => 'B',
            'explanation' => 'Metals reacting with dilute acids release hydrogen gas.'
        ],
        [
            'question_text' => 'The pH of a neutral solution at 25°C is?',
            'option_a' => '0',
            'option_b' => '7',
            'option_c' => '10',
            'option_d' => '14',
            'correct_option' => 'B',
            'explanation' => 'Neutral solutions have equal H+ and OH- concentrations, giving pH 7.'
        ],
        [
            'question_text' => 'Which type of bond involves sharing of electron pairs?',
            'option_a' => 'Ionic',
            'option_b' => 'Covalent',
            'option_c' => 'Metallic',
            'option_d' => 'Hydrogen',
            'correct_option' => 'B',
            'explanation' => 'Covalent bonds form when atoms share electron pairs.'
        ],
        [
            'question_text' => 'The process of converting a solid directly to a gas is called?',
            'option_a' => 'Condensation',
            'option_b' => 'Sublimation',
            'option_c' => 'Evaporation',
            'option_d' => 'Melting',
            'correct_option' => 'B',
            'explanation' => 'Sublimation is the direct change from solid to gas.'
        ],
        [
            'question_text' => 'Which of these is a strong acid?',
            'option_a' => 'Ethanoic acid',
            'option_b' => 'Hydrochloric acid',
            'option_c' => 'Carbonic acid',
            'option_d' => 'Citric acid',
            'correct_option' => 'B',
            'explanation' => 'Hydrochloric acid fully dissociates in water and is a strong acid.'
        ],
    ],
    'ENGLISH LANGUAGE' => [
        [
            'question_text' => 'Choose the option that correctly completes the sentence: The children ____ playing in the yard.',
            'option_a' => 'is',
            'option_b' => 'are',
            'option_c' => 'was',
            'option_d' => 'has',
            'correct_option' => 'B',
            'explanation' => '"Children" is plural, so the verb should be "are."'
        ],
        [
            'question_text' => 'Select the word that is closest in meaning to "generous."',
            'option_a' => 'Stingy',
            'option_b' => 'Kind',
            'option_c' => 'Careless',
            'option_d' => 'Harsh',
            'correct_option' => 'B',
            'explanation' => 'Generous means kind and willing to give.'
        ],
        [
            'question_text' => 'Identify the correctly punctuated sentence.',
            'option_a' => 'After dinner we watched a movie.',
            'option_b' => 'After dinner, we watched a movie.',
            'option_c' => 'After, dinner we watched a movie.',
            'option_d' => 'After dinner we watched, a movie.',
            'correct_option' => 'B',
            'explanation' => 'A comma after an introductory phrase is standard.'
        ],
        [
            'question_text' => 'Choose the correct form: If he ____ earlier, he would have caught the bus.',
            'option_a' => 'arrive',
            'option_b' => 'arrived',
            'option_c' => 'had arrived',
            'option_d' => 'was arriving',
            'correct_option' => 'C',
            'explanation' => 'Third conditional uses "had + past participle."'
        ],
        [
            'question_text' => 'Which option is a synonym for "brief"?',
            'option_a' => 'Long',
            'option_b' => 'Short',
            'option_c' => 'Wide',
            'option_d' => 'Heavy',
            'correct_option' => 'B',
            'explanation' => 'Brief means short in length or duration.'
        ],
    ],
    'PHYSICS' => [
        [
            'question_text' => 'The unit of electric charge is the?',
            'option_a' => 'Ohm',
            'option_b' => 'Coulomb',
            'option_c' => 'Volt',
            'option_d' => 'Watt',
            'correct_option' => 'B',
            'explanation' => 'Electric charge is measured in coulombs.'
        ],
        [
            'question_text' => 'A body moving in a circle at constant speed is said to be in?',
            'option_a' => 'Linear motion',
            'option_b' => 'Uniform circular motion',
            'option_c' => 'Simple harmonic motion',
            'option_d' => 'Random motion',
            'correct_option' => 'B',
            'explanation' => 'Constant speed along a circular path is uniform circular motion.'
        ],
        [
            'question_text' => 'Which law states that pressure in a fluid is transmitted equally in all directions?',
            'option_a' => 'Boyle\'s law',
            'option_b' => 'Pascal\'s principle',
            'option_c' => 'Charles\' law',
            'option_d' => 'Ohm\'s law',
            'correct_option' => 'B',
            'explanation' => 'Pascal\'s principle describes pressure transmission in fluids.'
        ],
        [
            'question_text' => 'The time for one complete oscillation is called?',
            'option_a' => 'Frequency',
            'option_b' => 'Amplitude',
            'option_c' => 'Period',
            'option_d' => 'Wavelength',
            'correct_option' => 'C',
            'explanation' => 'The period is the time for one full oscillation.'
        ],
        [
            'question_text' => 'Which device converts mechanical energy to electrical energy?',
            'option_a' => 'Motor',
            'option_b' => 'Generator',
            'option_c' => 'Transformer',
            'option_d' => 'Rectifier',
            'correct_option' => 'B',
            'explanation' => 'A generator converts mechanical energy into electrical energy.'
        ],
    ],
    'MATHEMATICS' => [
        [
            'question_text' => 'If 3x + 5 = 20, what is the value of x?',
            'option_a' => '3',
            'option_b' => '5',
            'option_c' => '7',
            'option_d' => '9',
            'correct_option' => 'B',
            'explanation' => '3x = 15, so x = 5.'
        ],
        [
            'question_text' => 'What is the perimeter of a rectangle with length 8cm and width 5cm?',
            'option_a' => '13cm',
            'option_b' => '26cm',
            'option_c' => '40cm',
            'option_d' => '16cm',
            'correct_option' => 'B',
            'explanation' => 'Perimeter = 2(length + width) = 2(8 + 5) = 26.'
        ],
        [
            'question_text' => 'Simplify: 2(3x - 4) + 5.',
            'option_a' => '6x - 3',
            'option_b' => '6x - 8',
            'option_c' => '6x - 11',
            'option_d' => '5x - 8',
            'correct_option' => 'A',
            'explanation' => '2(3x - 4) = 6x - 8, then +5 gives 6x - 3.'
        ],
        [
            'question_text' => 'The sum of the angles in a triangle is?',
            'option_a' => '90°',
            'option_b' => '180°',
            'option_c' => '270°',
            'option_d' => '360°',
            'correct_option' => 'B',
            'explanation' => 'The interior angles of a triangle add up to 180°.'
        ],
        [
            'question_text' => 'If a car travels 120 km in 3 hours, its average speed is?',
            'option_a' => '30 km/h',
            'option_b' => '40 km/h',
            'option_c' => '60 km/h',
            'option_d' => '90 km/h',
            'correct_option' => 'B',
            'explanation' => 'Average speed = distance/time = 120/3 = 40 km/h.'
        ],
    ],
    'GOVERNMENT' => [
        [
            'question_text' => 'A written constitution is one that is?',
            'option_a' => 'Based on customs only',
            'option_b' => 'Contained in a single document',
            'option_c' => 'Flexible and unwritten',
            'option_d' => 'Made by judges',
            'correct_option' => 'B',
            'explanation' => 'A written constitution is codified in a single document.'
        ],
        [
            'question_text' => 'The main function of the legislature is to?',
            'option_a' => 'Interpret laws',
            'option_b' => 'Make laws',
            'option_c' => 'Enforce laws',
            'option_d' => 'Adjudicate disputes',
            'correct_option' => 'B',
            'explanation' => 'The legislature is responsible for law-making.'
        ],
        [
            'question_text' => 'Universal adult suffrage means the right to vote is given to?',
            'option_a' => 'Only property owners',
            'option_b' => 'All adults meeting legal age',
            'option_c' => 'Only men',
            'option_d' => 'Only taxpayers',
            'correct_option' => 'B',
            'explanation' => 'It grants voting rights to all adults of legal age.'
        ],
        [
            'question_text' => 'The principle of separation of powers is meant to?',
            'option_a' => 'Concentrate power in one arm',
            'option_b' => 'Prevent abuse of power',
            'option_c' => 'Increase military control',
            'option_d' => 'Reduce elections',
            'correct_option' => 'B',
            'explanation' => 'Separation of powers prevents abuse by dividing functions among branches.'
        ],
        [
            'question_text' => 'In a federal system, powers are shared between?',
            'option_a' => 'Executive and judiciary',
            'option_b' => 'Central and state governments',
            'option_c' => 'Legislature and executive',
            'option_d' => 'State and local governments only',
            'correct_option' => 'B',
            'explanation' => 'Federalism divides powers between central and state governments.'
        ],
    ],
    'LITERATURE IN ENGLISH' => [
        [
            'question_text' => 'A work of fiction that is very short is called a?',
            'option_a' => 'Novel',
            'option_b' => 'Epic',
            'option_c' => 'Short story',
            'option_d' => 'Biography',
            'correct_option' => 'C',
            'explanation' => 'A short story is a brief work of fiction.'
        ],
        [
            'question_text' => 'The central message or insight in a literary work is its?',
            'option_a' => 'Theme',
            'option_b' => 'Plot',
            'option_c' => 'Setting',
            'option_d' => 'Character',
            'correct_option' => 'A',
            'explanation' => 'Theme refers to the main idea or message in a work.'
        ],
        [
            'question_text' => 'The repetition of consonant sounds in the middle or end of words is?',
            'option_a' => 'Alliteration',
            'option_b' => 'Assonance',
            'option_c' => 'Consonance',
            'option_d' => 'Rhyme',
            'correct_option' => 'C',
            'explanation' => 'Consonance is repetition of consonant sounds in nearby words.'
        ],
        [
            'question_text' => 'A play that ends in the downfall of the main character is a?',
            'option_a' => 'Comedy',
            'option_b' => 'Farce',
            'option_c' => 'Tragedy',
            'option_d' => 'Satire',
            'correct_option' => 'C',
            'explanation' => 'Tragedy depicts a serious downfall of the protagonist.'
        ],
        [
            'question_text' => 'A figure of speech that compares two things using "like" or "as" is a?',
            'option_a' => 'Metaphor',
            'option_b' => 'Simile',
            'option_c' => 'Personification',
            'option_d' => 'Irony',
            'correct_option' => 'B',
            'explanation' => 'Simile uses "like" or "as" to compare.'
        ],
    ],
    'ECONOMICS' => [
        [
            'question_text' => 'The basic economic problem is?',
            'option_a' => 'Inflation',
            'option_b' => 'Scarcity of resources',
            'option_c' => 'Unemployment',
            'option_d' => 'Deficit spending',
            'correct_option' => 'B',
            'explanation' => 'Resources are limited relative to unlimited wants.'
        ],
        [
            'question_text' => 'Demand refers to the willingness and ability to?',
            'option_a' => 'Produce goods',
            'option_b' => 'Buy goods at a given price',
            'option_c' => 'Sell goods at a given price',
            'option_d' => 'Tax goods',
            'correct_option' => 'B',
            'explanation' => 'Demand is the desire and ability to purchase at a price.'
        ],
        [
            'question_text' => 'Which of the following is an example of a public good?',
            'option_a' => 'Private car',
            'option_b' => 'Street lighting',
            'option_c' => 'Mobile phone',
            'option_d' => 'Personal computer',
            'correct_option' => 'B',
            'explanation' => 'Public goods like street lighting are non-excludable and non-rival.'
        ],
        [
            'question_text' => 'Opportunity cost is best described as?',
            'option_a' => 'Total cost of production',
            'option_b' => 'Value of the next best alternative forgone',
            'option_c' => 'Profit made from a decision',
            'option_d' => 'Cost of fixed assets',
            'correct_option' => 'B',
            'explanation' => 'It is the value of the best alternative you give up.'
        ],
        [
            'question_text' => 'A market where only one seller exists is called?',
            'option_a' => 'Oligopoly',
            'option_b' => 'Monopoly',
            'option_c' => 'Monopolistic competition',
            'option_d' => 'Perfect competition',
            'correct_option' => 'B',
            'explanation' => 'A monopoly has a single seller.'
        ],
    ],
    'COMMERCE' => [
        [
            'question_text' => 'A document sent by a buyer to a seller specifying goods to be supplied is a?',
            'option_a' => 'Quotation',
            'option_b' => 'Purchase order',
            'option_c' => 'Invoice',
            'option_d' => 'Receipt',
            'correct_option' => 'B',
            'explanation' => 'A purchase order is issued by the buyer to request goods.'
        ],
        [
            'question_text' => 'The channel of distribution refers to?',
            'option_a' => 'The place of production',
            'option_b' => 'The route goods take from producer to consumer',
            'option_c' => 'The price of goods',
            'option_d' => 'The packaging of goods',
            'correct_option' => 'B',
            'explanation' => 'It is the path goods follow from producers to consumers.'
        ],
        [
            'question_text' => 'Which of these is a function of insurance?',
            'option_a' => 'Creating scarcity',
            'option_b' => 'Sharing risk',
            'option_c' => 'Fixing prices',
            'option_d' => 'Reducing demand',
            'correct_option' => 'B',
            'explanation' => 'Insurance spreads risk among many policyholders.'
        ],
        [
            'question_text' => 'Which market condition has many buyers and sellers with similar products?',
            'option_a' => 'Monopoly',
            'option_b' => 'Oligopoly',
            'option_c' => 'Perfect competition',
            'option_d' => 'Duopoly',
            'correct_option' => 'C',
            'explanation' => 'Perfect competition has many buyers and sellers with homogeneous products.'
        ],
        [
            'question_text' => 'Trade credit refers to?',
            'option_a' => 'Loans from banks',
            'option_b' => 'Buying goods on credit from suppliers',
            'option_c' => 'Government subsidies',
            'option_d' => 'Personal savings',
            'correct_option' => 'B',
            'explanation' => 'Trade credit allows buyers to purchase now and pay later.'
        ],
    ],
    'CHRISTIAN RELIGIOUS STUDIES' => [
        [
            'question_text' => 'In the New Testament, the Sermon on the Mount is recorded in?',
            'option_a' => 'Mark',
            'option_b' => 'Matthew',
            'option_c' => 'Luke',
            'option_d' => 'John',
            'correct_option' => 'B',
            'explanation' => 'The Sermon on the Mount is found in Matthew chapters 5-7.'
        ],
        [
            'question_text' => 'Which disciple betrayed Jesus according to the Gospels?',
            'option_a' => 'Peter',
            'option_b' => 'John',
            'option_c' => 'Judas Iscariot',
            'option_d' => 'Matthew',
            'correct_option' => 'C',
            'explanation' => 'Judas Iscariot betrayed Jesus.'
        ],
        [
            'question_text' => 'The fruit of the Spirit listed by Paul includes all except?',
            'option_a' => 'Love',
            'option_b' => 'Joy',
            'option_c' => 'Patience',
            'option_d' => 'Anger',
            'correct_option' => 'D',
            'explanation' => 'Anger is not part of the fruit of the Spirit (Galatians 5:22-23).'
        ],
        [
            'question_text' => 'The first miracle of Jesus recorded in John is?',
            'option_a' => 'Feeding the five thousand',
            'option_b' => 'Turning water into wine',
            'option_c' => 'Healing the blind man',
            'option_d' => 'Walking on water',
            'correct_option' => 'B',
            'explanation' => 'At Cana, Jesus turned water into wine (John 2).'
        ],
        [
            'question_text' => 'Which event marks the coming of the Holy Spirit upon the disciples?',
            'option_a' => 'Passover',
            'option_b' => 'Pentecost',
            'option_c' => 'Epiphany',
            'option_d' => 'Ascension',
            'correct_option' => 'B',
            'explanation' => 'Pentecost commemorates the descent of the Holy Spirit.'
        ],
    ],
];

$subjects = Subject::all();
$insertedQuestions = 0;
$skippedSubjects = [];

foreach ($subjects as $subject) {
    $subjectKey = strtoupper($subject->name);
    if (!isset($questionBank[$subjectKey])) {
        $skippedSubjects[] = $subject->name;
        continue;
    }

    foreach ($questionBank[$subjectKey] as $questionData) {
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

echo "Questions added: {$insertedQuestions}\n";
if (!empty($skippedSubjects)) {
    echo "Subjects with no new questions (no question bank): " . implode(', ', $skippedSubjects) . "\n";
}
