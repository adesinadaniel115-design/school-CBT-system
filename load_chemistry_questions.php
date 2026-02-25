<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Chemistry subject ID
$chemistry = DB::table('subjects')->where('name', 'CHEMISTRY')->first();
if (!$chemistry) {
    echo "Chemistry subject not found. Creating it...\n";
    $chemistryId = DB::table('subjects')->insertGetId([
        'name' => 'CHEMISTRY',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
} else {
    $chemistryId = $chemistry->id;
    echo "Chemistry subject found with ID: $chemistryId\n";
}

$questions = [
    [
        'question' => 'A 5.6 g sample of an element combines completely with 3.2 g of oxygen to form its oxide. The empirical formula of the oxide is:',
        'a' => 'XO',
        'b' => 'X₂O',
        'c' => 'XO₂',
        'd' => 'X₂O₃',
        'answer' => 'B',
        'explanation' => 'Moles X = 5.6/M, O = 3.2/16 = 0.2 mol. If ratio simplifies to 2:1 → X₂O.',
    ],
    [
        'question' => 'When 25 cm³ of 0.2 M HCl reacts completely with NaOH, the volume of 0.1 M NaOH required is:',
        'a' => '10 cm³',
        'b' => '20 cm³',
        'c' => '25 cm³',
        'd' => '50 cm³',
        'answer' => 'D',
        'explanation' => 'M₁V₁ = M₂V₂ → 0.2×25 = 0.1×V → V=50 cm³.',
    ],
    [
        'question' => 'Which species has the highest bond energy?',
        'a' => 'Cl₂',
        'b' => 'O₂',
        'c' => 'N₂',
        'd' => 'F₂',
        'answer' => 'C',
        'explanation' => 'N≡N triple bond has highest bond energy.',
    ],
    [
        'question' => 'The pH of a solution formed by mixing equal volumes of 0.1 M HCl and 0.1 M NaOH is:',
        'a' => '1',
        'b' => '7',
        'c' => '9',
        'd' => '13',
        'answer' => 'B',
        'explanation' => 'Strong acid + strong base in equal amounts → neutral solution.',
    ],
    [
        'question' => 'If temperature increases, the equilibrium constant of an exothermic reaction:',
        'a' => 'Increases',
        'b' => 'Decreases',
        'c' => 'Remains constant',
        'd' => 'Becomes zero',
        'answer' => 'B',
        'explanation' => 'Heat acts as product; increasing temperature shifts backward.',
    ],
    [
        'question' => 'Which compound will undergo nucleophilic substitution most readily?',
        'a' => 'CH₃Cl',
        'b' => 'CH₃F',
        'c' => 'CH₃Br',
        'd' => 'CH₃I',
        'answer' => 'D',
        'explanation' => 'I⁻ is best leaving group; weaker bond → easier substitution.',
    ],
    [
        'question' => 'The number of moles of gas at STP occupying 44.8 dm³ is:',
        'a' => '1',
        'b' => '2',
        'c' => '0.5',
        'd' => '4',
        'answer' => 'B',
        'explanation' => '1 mole occupies 22.4 dm³ → 44.8/22.4 = 2 moles.',
    ],
    [
        'question' => 'Which ion has the smallest radius?',
        'a' => 'Na⁺',
        'b' => 'Mg²⁺',
        'c' => 'Al³⁺',
        'd' => 'O²⁻',
        'answer' => 'C',
        'explanation' => 'Greater positive charge pulls electrons closer.',
    ],
    [
        'question' => 'The oxidation number of Cr in K₂Cr₂O₇ is:',
        'a' => '+4',
        'b' => '+5',
        'c' => '+6',
        'd' => '+7',
        'answer' => 'C',
        'explanation' => '2(+1)+2x+7(−2)=0 → x=+6.',
    ],
    [
        'question' => 'If 0.5 mol of CaCO₃ decomposes completely, volume of CO₂ at STP produced is:',
        'a' => '5.6 dm³',
        'b' => '11.2 dm³',
        'c' => '22.4 dm³',
        'd' => '44.8 dm³',
        'answer' => 'B',
        'explanation' => '1 mol → 22.4 dm³, so 0.5 mol → 11.2 dm³.',
    ],
    [
        'question' => 'Which process increases entropy most?',
        'a' => 'Freezing water',
        'b' => 'Condensing steam',
        'c' => 'Dissolving salt in water',
        'd' => 'Crystallization',
        'answer' => 'C',
        'explanation' => 'Dissolving increases disorder.',
    ],
    [
        'question' => 'A buffer solution can be prepared by mixing:',
        'a' => 'Strong acid + strong base',
        'b' => 'Weak acid + its salt',
        'c' => 'Strong acid + water',
        'd' => 'Strong base + water',
        'answer' => 'B',
        'explanation' => 'Weak acid and its conjugate base resist pH change.',
    ],
    [
        'question' => 'Which hydrocarbon gives only one monochloro product?',
        'a' => 'Propane',
        'b' => 'Butane',
        'c' => '2-methylpropane',
        'd' => 'Methane',
        'answer' => 'D',
        'explanation' => 'All H atoms equivalent.',
    ],
    [
        'question' => 'The hybridization of carbon in CO₂ is:',
        'a' => 'sp',
        'b' => 'sp²',
        'c' => 'sp³',
        'd' => 'dsp²',
        'answer' => 'A',
        'explanation' => 'Linear molecule → sp hybridization.',
    ],
    [
        'question' => 'If rate doubles when concentration doubles, reaction is:',
        'a' => 'Zero order',
        'b' => 'First order',
        'c' => 'Second order',
        'd' => 'Third order',
        'answer' => 'B',
        'explanation' => 'Rate ∝ concentration¹.',
    ],
    [
        'question' => 'The molarity of a solution containing 9.8 g H₂SO₄ in 500 cm³ solution is:',
        'a' => '0.1 M',
        'b' => '0.2 M',
        'c' => '0.5 M',
        'd' => '1.0 M',
        'answer' => 'B',
        'explanation' => 'Molar mass=98 → 9.8g=0.1 mol → 0.1/0.5L=0.2M.',
    ],
    [
        'question' => 'Which salt will produce alkaline solution in water?',
        'a' => 'NH₄Cl',
        'b' => 'Na₂CO₃',
        'c' => 'KNO₃',
        'd' => 'HCl',
        'answer' => 'B',
        'explanation' => 'CO₃²⁻ hydrolyzes to produce OH⁻.',
    ],
    [
        'question' => 'The number of electrons in 2 moles of electrons is:',
        'a' => '6.02×10²³',
        'b' => '1.204×10²⁴',
        'c' => '3.01×10²³',
        'd' => '2.408×10²³',
        'answer' => 'B',
        'explanation' => '2 × 6.02×10²³.',
    ],
    [
        'question' => 'Which gas deviates most from ideal behavior at high pressure?',
        'a' => 'H₂',
        'b' => 'He',
        'c' => 'NH₃',
        'd' => 'Ne',
        'answer' => 'C',
        'explanation' => 'Strong intermolecular forces.',
    ],
    [
        'question' => 'The geometry of NH₃ is:',
        'a' => 'Linear',
        'b' => 'Trigonal planar',
        'c' => 'Pyramidal',
        'd' => 'Tetrahedral',
        'answer' => 'C',
        'explanation' => 'One lone pair causes pyramidal shape.',
    ],
    [
        'question' => 'When zinc reacts with dilute H₂SO₄, the oxidizing agent is:',
        'a' => 'Zn',
        'b' => 'H₂',
        'c' => 'SO₄²⁻',
        'd' => 'H⁺',
        'answer' => 'D',
        'explanation' => 'H⁺ gains electrons to form H₂.',
    ],
    [
        'question' => 'Which compound exhibits resonance?',
        'a' => 'CH₄',
        'b' => 'NH₃',
        'c' => 'CO₂',
        'd' => 'O₃',
        'answer' => 'D',
        'explanation' => 'Ozone has delocalized electrons.',
    ],
    [
        'question' => 'If 1 mol of ideal gas expands at constant temperature, internal energy change is:',
        'a' => 'Positive',
        'b' => 'Negative',
        'c' => 'Zero',
        'd' => 'Maximum',
        'answer' => 'C',
        'explanation' => 'Internal energy depends only on temperature.',
    ],
    [
        'question' => 'Which is the strongest acid?',
        'a' => 'HF',
        'b' => 'HCl',
        'c' => 'HBr',
        'd' => 'HI',
        'answer' => 'D',
        'explanation' => 'Acid strength increases down group.',
    ],
    [
        'question' => 'Which metal is extracted by electrolysis?',
        'a' => 'Iron',
        'b' => 'Copper',
        'c' => 'Sodium',
        'd' => 'Zinc',
        'answer' => 'C',
        'explanation' => 'Highly reactive metals require electrolysis.',
    ],
    [
        'question' => 'A reaction with activation energy lowered will:',
        'a' => 'Decrease rate',
        'b' => 'Increase rate',
        'c' => 'Stop reaction',
        'd' => 'Change equilibrium constant',
        'answer' => 'B',
        'explanation' => 'Lower Ea → more effective collisions.',
    ],
    [
        'question' => 'Which compound is optically active?',
        'a' => 'CH₃CH₂OH',
        'b' => 'CH₃CHClCH₃',
        'c' => 'CH₃CH₂CH₂Cl',
        'd' => 'CCl₄',
        'answer' => 'B',
        'explanation' => 'Contains chiral carbon.',
    ],
    [
        'question' => 'The oxidation state of Mn in KMnO₄ is:',
        'a' => '+4',
        'b' => '+5',
        'c' => '+6',
        'd' => '+7',
        'answer' => 'D',
        'explanation' => '+1 + x + 4(−2)=0 → x=+7.',
    ],
    [
        'question' => 'Which law relates pressure and volume at constant temperature?',
        'a' => 'Charles\' law',
        'b' => 'Boyle\'s law',
        'c' => 'Avogadro\'s law',
        'd' => 'Dalton\'s law',
        'answer' => 'B',
        'explanation' => 'P ∝ 1/V.',
    ],
    [
        'question' => 'If the concentration of reactant is tripled and rate increases ninefold, the reaction is:',
        'a' => 'First order',
        'b' => 'Second order',
        'c' => 'Third order',
        'd' => 'Zero order',
        'answer' => 'B',
        'explanation' => '3² = 9 → second order.',
    ],
];

$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $chemistryId,
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

// Show total questions in Chemistry
$total = DB::table('questions')->where('subject_id', $chemistryId)->count();
echo "\nTotal Chemistry questions in database: $total\n";
