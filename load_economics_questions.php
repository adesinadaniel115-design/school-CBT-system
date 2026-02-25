<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Economics subject ID
$economics = DB::table('subjects')->where('name', 'ECONOMICS')->first();
if ($economics) {
    echo "Economics subject found with ID: {$economics->id}\n";
    $economicsId = $economics->id;
} else {
    echo "Economics subject not found. Creating it...\n";
    $economicsId = DB::table('subjects')->insertGetId([
        'name' => 'ECONOMICS',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$questions = [
    [
        'question' => 'If the price of a good rises from ₦100 to ₦120 and quantity demanded falls from 50 to 40 units, the price elasticity of demand is:',
        'a' => '0.5',
        'b' => '0.8',
        'c' => '1.0',
        'd' => '1.5',
        'answer' => 'C',
        'explanation' => '%ΔQ = 10/50 = 0.2; %ΔP = 20/100 = 0.2 → Elasticity = 1 (unitary).',
    ],
    [
        'question' => 'A shift in demand curve to the right without change in price may result from:',
        'a' => 'Increase in production cost',
        'b' => 'Increase in consumer income (normal good)',
        'c' => 'Increase in tax',
        'd' => 'Fall in population',
        'answer' => 'B',
        'explanation' => 'Higher income increases demand for normal goods.',
    ],
    [
        'question' => 'Which situation best illustrates opportunity cost?',
        'a' => 'Paying tax to government',
        'b' => 'Choosing to attend school instead of working',
        'c' => 'Buying goods on credit',
        'd' => 'Saving money in bank',
        'answer' => 'B',
        'explanation' => 'Income forgone is the opportunity cost.',
    ],
    [
        'question' => 'If total cost is ₦500 when output is 20 units and ₦700 when output is 30 units, marginal cost of the 10 additional units is:',
        'a' => '₦10',
        'b' => '₦15',
        'c' => '₦20',
        'd' => '₦25',
        'answer' => 'C',
        'explanation' => 'MC = (700−500)/10 = ₦20.',
    ],
    [
        'question' => 'In perfect competition, a firm maximizes profit when:',
        'a' => 'AR > MR',
        'b' => 'MR = MC',
        'c' => 'MC > MR',
        'd' => 'TR is minimum',
        'answer' => 'B',
        'explanation' => 'Profit maximization occurs at MR = MC.',
    ],
    [
        'question' => 'Which is NOT a feature of monopoly?',
        'a' => 'Single seller',
        'b' => 'Close substitutes',
        'c' => 'Price maker',
        'd' => 'Barriers to entry',
        'answer' => 'B',
        'explanation' => 'Monopoly has no close substitutes.',
    ],
    [
        'question' => 'If MPC = 0.8, the simple multiplier is:',
        'a' => '2',
        'b' => '3',
        'c' => '4',
        'd' => '5',
        'answer' => 'D',
        'explanation' => 'Multiplier = 1/(1−0.8) = 5.',
    ],
    [
        'question' => 'Inflation caused by rising production costs is known as:',
        'a' => 'Demand-pull inflation',
        'b' => 'Structural inflation',
        'c' => 'Cost-push inflation',
        'd' => 'Imported inflation',
        'answer' => 'C',
        'explanation' => 'Rising input cost pushes prices upward.',
    ],
    [
        'question' => 'If a country\'s imports exceed exports, it experiences:',
        'a' => 'Surplus',
        'b' => 'Deficit',
        'c' => 'Balanced trade',
        'd' => 'Appreciation',
        'answer' => 'B',
        'explanation' => 'Imports > exports = trade deficit.',
    ],
    [
        'question' => 'Which factor shifts supply curve to the right?',
        'a' => 'Increase in wages',
        'b' => 'Increase in taxation',
        'c' => 'Improvement in technology',
        'd' => 'Increase in cost of raw materials',
        'answer' => 'C',
        'explanation' => 'Better technology increases supply.',
    ],
    [
        'question' => 'If price elasticity of supply is 0.4, supply is:',
        'a' => 'Elastic',
        'b' => 'Inelastic',
        'c' => 'Unitary',
        'd' => 'Perfectly elastic',
        'answer' => 'B',
        'explanation' => 'Elasticity less than 1 = inelastic.',
    ],
    [
        'question' => 'Total utility is maximized when:',
        'a' => 'MU = 0',
        'b' => 'MU is rising',
        'c' => 'MU is negative',
        'd' => 'TU is decreasing',
        'answer' => 'A',
        'explanation' => 'TU is maximum when MU = 0.',
    ],
    [
        'question' => 'A regressive tax is one where:',
        'a' => 'Tax rate increases with income',
        'b' => 'Tax burden falls heavily on low income earners',
        'c' => 'Everyone pays equal rate',
        'd' => 'Corporations are exempted',
        'answer' => 'B',
        'explanation' => 'Lower-income earners bear higher proportion.',
    ],
    [
        'question' => 'If population grows faster than food production, according to Malthus, it results in:',
        'a' => 'Surplus',
        'b' => 'Inflation',
        'c' => 'Poverty and famine',
        'd' => 'Industrial growth',
        'answer' => 'C',
        'explanation' => 'Food grows arithmetically; population geometrically.',
    ],
    [
        'question' => 'Which is a function of Central Bank?',
        'a' => 'Accepting savings deposits',
        'b' => 'Lending to small traders',
        'c' => 'Banker to government',
        'd' => 'Selling consumer goods',
        'answer' => 'C',
        'explanation' => 'Central Bank serves government.',
    ],
    [
        'question' => 'If total revenue is ₦10,000 at 100 units output, average revenue is:',
        'a' => '₦10',
        'b' => '₦50',
        'c' => '₦100',
        'd' => '₦1,000',
        'answer' => 'C',
        'explanation' => 'AR = TR/Q = 10,000/100 = 100.',
    ],
    [
        'question' => 'Which economic system allows price mechanism to allocate resources?',
        'a' => 'Socialist',
        'b' => 'Mixed',
        'c' => 'Capitalist',
        'd' => 'Traditional',
        'answer' => 'C',
        'explanation' => 'Capitalism relies on price mechanism.',
    ],
    [
        'question' => 'An outward shift of PPC indicates:',
        'a' => 'Unemployment',
        'b' => 'Economic growth',
        'c' => 'Inflation',
        'd' => 'Scarcity',
        'answer' => 'B',
        'explanation' => 'Increase in productive capacity.',
    ],
    [
        'question' => 'If exchange rate changes from ₦500/$ to ₦700/$, the naira has:',
        'a' => 'Appreciated',
        'b' => 'Depreciated',
        'c' => 'Remained constant',
        'd' => 'Strengthened',
        'answer' => 'B',
        'explanation' => 'More naira needed per dollar.',
    ],
    [
        'question' => 'Which is an example of fixed cost?',
        'a' => 'Raw materials',
        'b' => 'Wages of casual workers',
        'c' => 'Rent',
        'd' => 'Transport cost per unit',
        'answer' => 'C',
        'explanation' => 'Rent does not vary with output.',
    ],
    [
        'question' => 'Which situation leads to excess demand?',
        'a' => 'Price above equilibrium',
        'b' => 'Price below equilibrium',
        'c' => 'High supply',
        'd' => 'Tax increase',
        'answer' => 'B',
        'explanation' => 'Lower price increases demand beyond supply.',
    ],
    [
        'question' => 'A firm producing at minimum average cost is said to be:',
        'a' => 'Technically efficient',
        'b' => 'Productively efficient',
        'c' => 'Allocatively inefficient',
        'd' => 'Revenue maximizing',
        'answer' => 'B',
        'explanation' => 'Lowest average cost = productive efficiency.',
    ],
    [
        'question' => 'If APC = 0.3, then APS is:',
        'a' => '0.3',
        'b' => '0.7',
        'c' => '1.0',
        'd' => '1.3',
        'answer' => 'B',
        'explanation' => 'APC + APS = 1.',
    ],
    [
        'question' => 'Which policy reduces inflation?',
        'a' => 'Expansionary fiscal policy',
        'b' => 'Increased government spending',
        'c' => 'Contractionary monetary policy',
        'd' => 'Lower taxation',
        'answer' => 'C',
        'explanation' => 'Reduces money supply.',
    ],
    [
        'question' => 'Division of labour increases output mainly by:',
        'a' => 'Increasing wages',
        'b' => 'Reducing competition',
        'c' => 'Improving specialization',
        'd' => 'Increasing taxes',
        'answer' => 'C',
        'explanation' => 'Specialization boosts efficiency.',
    ],
    [
        'question' => 'If demand is perfectly inelastic, elasticity equals:',
        'a' => '0',
        'b' => '1',
        'c' => 'Infinity',
        'd' => '−1',
        'answer' => 'A',
        'explanation' => 'Quantity does not respond to price.',
    ],
    [
        'question' => 'A rise in interest rate will likely:',
        'a' => 'Increase investment',
        'b' => 'Reduce savings',
        'c' => 'Reduce borrowing',
        'd' => 'Increase consumption',
        'answer' => 'C',
        'explanation' => 'High rates discourage borrowing.',
    ],
    [
        'question' => 'National income at factor cost excludes:',
        'a' => 'Wages',
        'b' => 'Rent',
        'c' => 'Indirect taxes',
        'd' => 'Interest',
        'answer' => 'C',
        'explanation' => 'Indirect taxes are removed to get factor cost.',
    ],
    [
        'question' => 'The major problem of barter system is:',
        'a' => 'Inflation',
        'b' => 'Double coincidence of wants',
        'c' => 'Deflation',
        'd' => 'Surplus',
        'answer' => 'B',
        'explanation' => 'Needs mutual wants.',
    ],
    [
        'question' => 'Which is a characteristic of oligopoly?',
        'a' => 'Many sellers',
        'b' => 'Single seller',
        'c' => 'Few large firms',
        'd' => 'Free entry',
        'answer' => 'C',
        'explanation' => 'Oligopoly has few dominant firms.',
    ],
    [
        'question' => 'Real GDP differs from nominal GDP because it:',
        'a' => 'Includes taxes',
        'b' => 'Excludes imports',
        'c' => 'Is adjusted for inflation',
        'd' => 'Includes exports',
        'answer' => 'C',
        'explanation' => 'Real GDP removes price effect.',
    ],
    [
        'question' => 'If government increases taxes during inflation, aggregate demand will:',
        'a' => 'Increase',
        'b' => 'Decrease',
        'c' => 'Remain constant',
        'd' => 'Double',
        'answer' => 'B',
        'explanation' => 'Higher tax reduces disposable income.',
    ],
    [
        'question' => 'Which type of unemployment occurs due to technological advancement?',
        'a' => 'Frictional',
        'b' => 'Structural',
        'c' => 'Seasonal',
        'd' => 'Cyclical',
        'answer' => 'B',
        'explanation' => 'Skill mismatch from technology.',
    ],
    [
        'question' => 'A fall in price of complementary good will:',
        'a' => 'Decrease demand',
        'b' => 'Increase demand',
        'c' => 'Reduce supply',
        'd' => 'Increase supply',
        'answer' => 'B',
        'explanation' => 'Complement price drop boosts demand.',
    ],
    [
        'question' => 'The Gini coefficient measures:',
        'a' => 'Inflation',
        'b' => 'Poverty',
        'c' => 'Income inequality',
        'd' => 'Growth rate',
        'answer' => 'C',
        'explanation' => 'Measures income distribution inequality.',
    ],
    [
        'question' => 'A decrease in money supply will likely cause:',
        'a' => 'Inflation',
        'b' => 'Deflation',
        'c' => 'Boom',
        'd' => 'Excess demand',
        'answer' => 'B',
        'explanation' => 'Less money → lower prices.',
    ],
    [
        'question' => 'Which is an invisible export?',
        'a' => 'Crude oil',
        'b' => 'Cocoa',
        'c' => 'Shipping services',
        'd' => 'Machinery',
        'answer' => 'C',
        'explanation' => 'Services are invisible exports.',
    ],
    [
        'question' => 'Short-run average cost curve is U-shaped due to:',
        'a' => 'Inflation',
        'b' => 'Law of diminishing returns',
        'c' => 'Monopoly power',
        'd' => 'Government policy',
        'answer' => 'B',
        'explanation' => 'Diminishing returns raise costs after some point.',
    ],
    [
        'question' => 'Capital deepening refers to:',
        'a' => 'Increase in labour force',
        'b' => 'Increase in capital per worker',
        'c' => 'Increase in exports',
        'd' => 'Increase in taxation',
        'answer' => 'B',
        'explanation' => 'More capital per worker increases productivity.',
    ],
    [
        'question' => 'Which is NOT included in GDP?',
        'a' => 'Final goods',
        'b' => 'Government services',
        'c' => 'Transfer payments',
        'd' => 'Investment spending',
        'answer' => 'C',
        'explanation' => 'Transfer payments are not production.',
    ],
];

$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $economicsId,
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

// Show total questions in Economics
$total = DB::table('questions')->where('subject_id', $economicsId)->count();
echo "\nTotal Economics questions in database: $total\n";
