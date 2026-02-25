<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Biology subject ID
$biology = DB::table('subjects')->where('name', 'BIOLOGY')->first();
if ($biology) {
    echo "Biology subject found with ID: {$biology->id}\n";
    $biologyId = $biology->id;
} else {
    echo "Biology subject not found. Creating it...\n";
    $biologyId = DB::table('subjects')->insertGetId([
        'name' => 'BIOLOGY',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$questions = [
    [
        'question' => 'Which of the following organelles is known as the "powerhouse" of the cell?',
        'a' => 'Nucleus',
        'b' => 'Mitochondrion',
        'c' => 'Ribosome',
        'd' => 'Golgi body',
        'answer' => 'B',
        'explanation' => 'The mitochondrion is where aerobic respiration occurs to produce ATP (energy).',
    ],
    [
        'question' => 'The level of organization of an onion bulb is a/an:',
        'a' => 'Cell',
        'b' => 'Tissue',
        'c' => 'Organ',
        'd' => 'System',
        'answer' => 'C',
        'explanation' => 'An onion bulb consists of several tissues working together, making it an organ.',
    ],
    [
        'question' => 'In a plant cell, the vacuole is mainly used for:',
        'a' => 'Protein synthesis',
        'b' => 'Storage of cell sap',
        'c' => 'Photosynthesis',
        'd' => 'Cell division',
        'answer' => 'B',
        'explanation' => 'The large central vacuole stores water, waste, and nutrients as cell sap.',
    ],
    [
        'question' => 'Which of these organisms is a prokaryote?',
        'a' => 'Amoeba',
        'b' => 'Euglena',
        'c' => 'Bacteria',
        'd' => 'Mushroom',
        'answer' => 'C',
        'explanation' => 'Bacteria belong to Kingdom Monera and lack a defined nucleus.',
    ],
    [
        'question' => 'The movement of Euglena towards light is an example of:',
        'a' => 'Phototropism',
        'b' => 'Phototaxis',
        'c' => 'Photosynthesis',
        'd' => 'Photonasty',
        'answer' => 'B',
        'explanation' => 'Tactic movements (taxis) involve the whole organism moving towards a stimulus.',
    ],
    [
        'question' => 'Which of the following is an autotrophic organism?',
        'a' => 'Rhizopus',
        'b' => 'Spirogyra',
        'c' => 'Tapeworm',
        'd' => 'Mushroom',
        'answer' => 'B',
        'explanation' => 'Spirogyra contains chlorophyll and produces its own food via photosynthesis.',
    ],
    [
        'question' => 'The dental formula of an adult human is:',
        'a' => '2/2, 1/1, 2/2, 3/3',
        'b' => '3/3, 1/1, 4/4, 2/2',
        'c' => '2/2, 0/0, 3/3, 3/3',
        'd' => '1/1, 2/2, 2/2, 3/3',
        'answer' => 'A',
        'explanation' => 'Humans have 2 incisors, 1 canine, 2 premolars, and 3 molars per quadrant.',
    ],
    [
        'question' => 'The end product of protein digestion is:',
        'a' => 'Glucose',
        'b' => 'Fatty acids',
        'c' => 'Amino acids',
        'd' => 'Glycerol',
        'answer' => 'C',
        'explanation' => 'Proteases break down proteins into their building blocks, amino acids.',
    ],
    [
        'question' => 'Which enzyme is responsible for the digestion of starch in the mouth?',
        'a' => 'Pepsin',
        'b' => 'Ptyalin (Salivary Amylase)',
        'c' => 'Trypsin',
        'd' => 'Lipase',
        'answer' => 'B',
        'explanation' => 'Ptyalin starts the chemical breakdown of cooked starch into maltose.',
    ],
    [
        'question' => 'A deficiency of Vitamin C leads to:',
        'a' => 'Rickets',
        'b' => 'Scurvy',
        'c' => 'Beriberi',
        'd' => 'Night blindness',
        'answer' => 'B',
        'explanation' => 'Vitamin C (ascorbic acid) is vital for skin and gum health; deficiency causes scurvy.',
    ],
    [
        'question' => 'The process of maintaining a constant internal environment is called:',
        'a' => 'Respiration',
        'b' => 'Excretion',
        'c' => 'Homeostasis',
        'd' => 'Osmosis',
        'answer' => 'C',
        'explanation' => 'Homeostasis regulates factors like temperature and pH.',
    ],
    [
        'question' => 'Which of these structures is used for gaseous exchange in a fish?',
        'a' => 'Trachea',
        'b' => 'Lungs',
        'c' => 'Gills',
        'd' => 'Stomata',
        'answer' => 'C',
        'explanation' => 'Gills allow fish to extract dissolved oxygen from water.',
    ],
    [
        'question' => 'In humans, deamination of excess amino acids occurs in the:',
        'a' => 'Kidney',
        'b' => 'Liver',
        'c' => 'Pancreas',
        'd' => 'Small intestine',
        'answer' => 'B',
        'explanation' => 'The liver removes nitrogen from amino acids to form urea.',
    ],
    [
        'question' => 'The functional unit of the mammalian kidney is the:',
        'a' => 'Nephridium',
        'b' => 'Flame cell',
        'c' => 'Nephron',
        'd' => 'Ureter',
        'answer' => 'C',
        'explanation' => 'The nephron is responsible for filtration and urine formation.',
    ],
    [
        'question' => 'Which of these is a respiratory organ in an insect?',
        'a' => 'Skin',
        'b' => 'Gills',
        'c' => 'Trachea',
        'd' => 'Lungs',
        'answer' => 'C',
        'explanation' => 'Insects use a system of tracheal tubes for gas exchange.',
    ],
    [
        'question' => 'The tissue responsible for the transport of water in plants is:',
        'a' => 'Phloem',
        'b' => 'Xylem',
        'c' => 'Cambium',
        'd' => 'Cortex',
        'answer' => 'B',
        'explanation' => 'Xylem vessels conduct water and minerals from roots to leaves.',
    ],
    [
        'question' => 'The liquid part of blood that contains nutrients and hormones is:',
        'a' => 'Red blood cells',
        'b' => 'White blood cells',
        'c' => 'Plasma',
        'd' => 'Platelets',
        'answer' => 'C',
        'explanation' => 'Plasma is the fluid medium for transporting dissolved substances.',
    ],
    [
        'question' => 'A person with blood group O is called a universal donor because:',
        'a' => 'They have no antibodies',
        'b' => 'They have no antigens',
        'c' => 'They have both antigens',
        'd' => 'They have A and B antibodies',
        'answer' => 'B',
        'explanation' => 'Group O lacks A and B antigens on red cells, preventing rejection by other groups.',
    ],
    [
        'question' => 'Transpiration is the loss of water vapor through the:',
        'a' => 'Roots',
        'b' => 'Xylem',
        'c' => 'Stomata',
        'd' => 'Phloem',
        'answer' => 'C',
        'explanation' => 'Water vapor escapes mainly through leaf stomata.',
    ],
    [
        'question' => 'The hormone that regulates blood sugar levels is:',
        'a' => 'Adrenaline',
        'b' => 'Thyroxine',
        'c' => 'Insulin',
        'd' => 'Auxin',
        'answer' => 'C',
        'explanation' => 'Insulin, produced by the pancreas, lowers blood glucose levels.',
    ],
    [
        'question' => 'The part of the brain that controls balance and posture is the:',
        'a' => 'Cerebrum',
        'b' => 'Cerebellum',
        'c' => 'Medulla oblongata',
        'd' => 'Thalamus',
        'answer' => 'B',
        'explanation' => 'The cerebellum coordinates voluntary movements and balance.',
    ],
    [
        'question' => 'Which of these is a reflex action?',
        'a' => 'Solving a math problem',
        'b' => 'Blinking when dust enters the eye',
        'c' => 'Writing a letter',
        'd' => 'Driving a car',
        'answer' => 'B',
        'explanation' => 'Reflexes are rapid, involuntary responses to stimuli.',
    ],
    [
        'question' => 'The light-sensitive layer of the mammalian eye is the:',
        'a' => 'Sclera',
        'b' => 'Retina',
        'c' => 'Choroid',
        'd' => 'Cornea',
        'answer' => 'B',
        'explanation' => 'The retina contains photoreceptors (rods and cones) that detect light.',
    ],
    [
        'question' => 'Which plant hormone is responsible for fruit ripening?',
        'a' => 'Auxin',
        'b' => 'Gibberellin',
        'c' => 'Ethylene',
        'd' => 'Cytokinin',
        'answer' => 'C',
        'explanation' => 'Ethylene is a gaseous hormone that triggers fruit ripening.',
    ],
    [
        'question' => 'A support tissue in plants that provides flexibility is:',
        'a' => 'Sclerenchyma',
        'b' => 'Xylem',
        'c' => 'Collenchyma',
        'd' => 'Phloem',
        'answer' => 'C',
        'explanation' => 'Collenchyma provides support to young, growing plant parts.',
    ],
    [
        'question' => 'The type of joint found in the human shoulder is:',
        'a' => 'Hinge joint',
        'b' => 'Ball and socket joint',
        'c' => 'Pivot joint',
        'd' => 'Gliding joint',
        'answer' => 'B',
        'explanation' => 'Ball and socket joints allow movement in many directions.',
    ],
    [
        'question' => 'Asexual reproduction in yeast occurs by:',
        'a' => 'Binary fission',
        'b' => 'Budding',
        'c' => 'Spore formation',
        'd' => 'Fragmentation',
        'answer' => 'B',
        'explanation' => 'Yeast produces small "buds" that eventually detach as new cells.',
    ],
    [
        'question' => 'The part of the flower that develops into a fruit after fertilization is the:',
        'a' => 'Ovule',
        'b' => 'Ovary',
        'c' => 'Stigma',
        'd' => 'Style',
        'answer' => 'B',
        'explanation' => 'The ovary becomes the fruit, while ovules become seeds.',
    ],
    [
        'question' => 'Hypogeal germination is characterized by:',
        'a' => 'The cotyledons remaining below the soil',
        'b' => 'The cotyledons emerging above the soil',
        'c' => 'Fast growth of the hypocotyl',
        'd' => 'Growth in light only',
        'answer' => 'A',
        'explanation' => 'In hypogeal germination (e.g., maize), the epicotyl elongates, keeping cotyledons underground.',
    ],
    [
        'question' => 'Which of the following is a male reproductive organ in plants?',
        'a' => 'Carpel',
        'b' => 'Stamen',
        'c' => 'Style',
        'd' => 'Ovary',
        'answer' => 'B',
        'explanation' => 'The stamen consists of the anther and filament.',
    ],
    [
        'question' => 'The primary source of energy in an ecosystem is:',
        'a' => 'Green plants',
        'b' => 'Decomposition',
        'c' => 'The Sun',
        'd' => 'Herbivores',
        'answer' => 'C',
        'explanation' => 'Solar energy is captured by producers via photosynthesis.',
    ],
    [
        'question' => 'An association where both organisms benefit is called:',
        'a' => 'Parasitism',
        'b' => 'Commensalism',
        'c' => 'Mutualism',
        'd' => 'Predation',
        'answer' => 'C',
        'explanation' => 'Mutualism is a "win-win" symbiotic interaction.',
    ],
    [
        'question' => 'Which of the following is an abiotic factor?',
        'a' => 'Bacteria',
        'b' => 'Temperature',
        'c' => 'Fungi',
        'd' => 'Grass',
        'answer' => 'B',
        'explanation' => 'Abiotic factors are non-living components of an environment.',
    ],
    [
        'question' => 'The process by which nitrogen is returned to the atmosphere is:',
        'a' => 'Nitrogen fixation',
        'b' => 'Nitrification',
        'c' => 'Denitrification',
        'd' => 'Decomposition',
        'answer' => 'C',
        'explanation' => 'Denitrifying bacteria convert nitrates back into nitrogen gas.',
    ],
    [
        'question' => 'A cross between a tall plant (TT) and a short plant (tt) will result in offspring that are all:',
        'a' => 'Tall',
        'b' => 'Short',
        'c' => 'Medium height',
        'd' => '50% tall, 50% short',
        'answer' => 'A',
        'explanation' => 'All offspring (Tt) will show the dominant phenotype (tall).',
    ],
    [
        'question' => 'Sex-linked traits are usually carried on the:',
        'a' => 'Autosomes',
        'b' => 'X chromosome',
        'c' => 'Y chromosome',
        'd' => 'Ribosomes',
        'answer' => 'B',
        'explanation' => 'Most sex-linked traits (like color blindness) are X-linked.',
    ],
    [
        'question' => 'Which of the following describes the physical appearance of an organism?',
        'a' => 'Genotype',
        'b' => 'Phenotype',
        'c' => 'Allele',
        'd' => 'Gamete',
        'answer' => 'B',
        'explanation' => 'Phenotype is the observable expression of a genotype.',
    ],
    [
        'question' => 'The theory of "Survival of the Fittest" was proposed by:',
        'a' => 'Jean Lamarck',
        'b' => 'Charles Darwin',
        'c' => 'Gregor Mendel',
        'd' => 'Robert Hooke',
        'answer' => 'B',
        'explanation' => 'Darwin\'s theory explains evolution through natural selection.',
    ],
    [
        'question' => 'Evidence of evolution from the study of fossils is known as:',
        'a' => 'Comparative anatomy',
        'b' => 'Embryology',
        'c' => 'Paleontology',
        'd' => 'Genetics',
        'answer' => 'C',
        'explanation' => 'Paleontology is the study of prehistoric life through fossils.',
    ],
    [
        'question' => 'An example of a vestigial organ in humans is the:',
        'a' => 'Heart',
        'b' => 'Appendix',
        'c' => 'Liver',
        'd' => 'Kidney',
        'answer' => 'B',
        'explanation' => 'Vestigial organs are structures that have lost their original function over time.',
    ],
];

$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $biologyId,
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

// Show total questions in Biology
$total = DB::table('questions')->where('subject_id', $biologyId)->count();
echo "\nTotal Biology questions in database: $total\n";
