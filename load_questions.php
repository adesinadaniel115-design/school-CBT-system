<?php
$host = 'localhost';
$db = 'school_cbt_db';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Clear existing questions
$mysqli->query("SET FOREIGN_KEY_CHECKS=0");
$mysqli->query("TRUNCATE TABLE questions");
$mysqli->query("SET FOREIGN_KEY_CHECKS=1");

$questions = [
    ['BIOLOGY', 'Which of the following describes the function of the contractile vacuole in Amoeba?', 'Digestion', 'Osmoregulation', 'Respiration', 'Movement', 'B', 'medium', 'The contractile vacuole regulates water content by collecting and expelling excess water.'],
    ['BIOLOGY', 'In the human heart the bicuspid valve is located between the?', 'Left auricle and left ventricle', 'Right auricle and right ventricle', 'Left ventricle and aorta', 'Right ventricle and pulmonary artery', 'A', 'hard', 'The bicuspid or mitral valve prevents the backflow of blood from the left ventricle to the left auricle.'],
    ['BIOLOGY', 'Which of the following is a characteristic of wind-pollinated flowers?', 'Brightly colored petals', 'Scented flowers', 'Long filaments and feathery stigmas', 'Small sticky pollen grains', 'C', 'medium', 'Wind-pollinated flowers have exposed anthers and feathery stigmas to catch airborne pollen.'],
    ['BIOLOGY', 'The relationship between a tapeworm and the human intestine is an example of?', 'Commensalism', 'Symbiosis', 'Parasitism', 'Saprophytism', 'C', 'medium', 'The tapeworm benefits at the expense of the human host by absorbing digested food.'],
    ['BIOLOGY', 'Which of the following is the respiratory organ of an insect?', 'Gills', 'Lung books', 'Tracheae', 'Lungs', 'C', 'medium', 'Insects breathe through a system of tubes called tracheae which open to the outside via spiracles.'],
    ['CHEMISTRY', 'The constituent of air that supports combustion is?', 'Nitrogen', 'Oxygen', 'Carbon dioxide', 'Argon', 'B', 'medium', 'Oxygen is chemically required for the process of burning or combustion.'],
    ['CHEMISTRY', 'Which of the following is a physical change?', 'Burning of wood', 'Rusting of iron', 'Melting of ice', 'Souring of milk', 'C', 'medium', 'Melting is a change of state where no new chemical substance is formed.'],
    ['CHEMISTRY', 'What is the common name for the compound Calcium Carbonate?', 'Quicklime', 'Slaked lime', 'Limestone', 'Gypsum', 'C', 'medium', 'Limestone is a sedimentary rock composed largely of the mineral calcium carbonate.'],
    ['CHEMISTRY', 'The separation technique used in the production of table salt from seawater is?', 'Distillation', 'Evaporation', 'Filtration', 'Sublimation', 'B', 'medium', 'Evaporation removes the liquid solvent (water) to leave behind the solid solute (salt).'],
    ['CHEMISTRY', 'Which of the following is a member of the Alkyne series?', 'Methane', 'Ethene', 'Ethyne', 'Propane', 'C', 'hard', 'Alkynes are unsaturated hydrocarbons characterized by a carbon to carbon triple bond.'],
    ['ENGLISH LANGUAGE', 'Select the best interpretation for the idiom: To make a long story short.', 'To tell a lie', 'To summarize', 'To speak slowly', 'To win an argument', 'B', 'medium', 'This idiom is used when a person skips over unnecessary details to reach the main point.'],
    ['ENGLISH LANGUAGE', 'Choose the word that has the same vowel sound as the one in the word Key.', 'Day', 'Feet', 'Play', 'Wait', 'B', 'medium', 'Key and Feet share the long e sound.'],
    ['ENGLISH LANGUAGE', 'Identify the option nearest in meaning to the bold word: The soldier was valiant in battle.', 'Cowardly', 'Brave', 'Weak', 'Careless', 'B', 'medium', 'Valiant means showing courage or determination.'],
    ['ENGLISH LANGUAGE', 'Choose the option opposite in meaning to the bold word: The witness gave a detailed account.', 'Brief', 'Long', 'Clear', 'Vivid', 'A', 'medium', 'Detailed means thorough; the opposite is brief or concise.'],
    ['ENGLISH LANGUAGE', 'Select the correct word to fill the gap: Neither the teacher nor the students _____ present.', 'is', 'are', 'was', 'has', 'B', 'hard', 'When using neither/nor, the verb agrees with the closer subject (students), which is plural.'],
    ['PHYSICS', 'The energy possessed by a body due to its motion is?', 'Potential energy', 'Kinetic energy', 'Chemical energy', 'Electrical energy', 'B', 'medium', 'Kinetic energy is the work needed to accelerate a body of a given mass from rest.'],
    ['PHYSICS', 'Which of the following instruments is used to measure current?', 'Voltmeter', 'Ammeter', 'Galvanometer', 'Ohm meter', 'B', 'medium', 'An ammeter is a measuring instrument used to measure the current in a circuit.'],
    ['PHYSICS', 'The change in the direction of light as it passes from one medium to another is?', 'Reflection', 'Refraction', 'Dispersion', 'Diffraction', 'B', 'medium', 'Refraction is the bending of light caused by a change in its speed as it enters a new medium.'],
    ['PHYSICS', 'Which of the following is a vector quantity?', 'Mass', 'Distance', 'Displacement', 'Time', 'C', 'medium', 'Displacement has both magnitude and direction, making it a vector.'],
    ['PHYSICS', 'The clinical thermometer is characterized by having a?', 'Long stem', 'Narrow bore and constriction', 'Wide bore', 'Plastic body', 'B', 'hard', 'The constriction prevents mercury from falling back into the bulb before a reading is taken.'],
    ['MATHEMATICS', 'If the mean of 3, 5, 8, and x is 6, find the value of x.', '6', '8', '10', '12', 'B', 'medium', 'Summing the numbers (16 + x) and dividing by 4 equals 6. 16 + x = 24, so x = 8.'],
    ['MATHEMATICS', 'What is the value of 5 factorial?', '20', '60', '120', '240', 'C', 'medium', 'Factorial 5 is 5 multiplied by 4, 3, 2, and 1, which equals 120.'],
    ['MATHEMATICS', 'Find the simple interest on 2000 naira for 3 years at 10 percent.', '200', '400', '600', '800', 'C', 'medium', 'Interest = (Principal times Rate times Time) / 100. (2000 times 10 times 3) / 100 = 600.'],
    ['MATHEMATICS', 'The sum of the interior angles of a quadrilateral is?', '180', '270', '360', '540', 'C', 'medium', 'Any four-sided polygon has interior angles that sum to 360 degrees.'],
    ['MATHEMATICS', 'Calculate the volume of a cube with a side length of 4cm.', '16', '32', '64', '128', 'C', 'medium', 'Volume of a cube is side cubed. 4 multiplied by 4 multiplied by 4 equals 64.'],
    ['GOVERNMENT', 'A system of government where the military holds power is a?', 'Democracy', 'Monarchy', 'Stratocracy', 'Theocracy', 'C', 'hard', 'Stratocracy is a form of government headed by military chiefs.'],
    ['GOVERNMENT', 'The main function of the Judiciary is to?', 'Make laws', 'Enforce laws', 'Interpret laws', 'Draft laws', 'C', 'medium', 'The Judiciary explains the meaning of laws and applies them to individual cases.'],
    ['GOVERNMENT', 'The 1979 Constitution of Nigeria introduced which system of government?', 'Parliamentary', 'Presidential', 'Unitary', 'Monarchy', 'B', 'hard', 'The 1979 Constitution marked the shift from the British parliamentary style to the US presidential style.'],
    ['GOVERNMENT', 'The head of the Commonwealth of Nations is the?', 'British Prime Minister', 'United Nations Secretary General', 'British Monarch', 'Secretary General of the Commonwealth', 'C', 'medium', 'The British Monarch is the symbolic head of the Commonwealth.'],
    ['GOVERNMENT', 'A referendum is a vote taken to decide on a?', 'New tax', 'Budget', 'Constitutional or public issue', 'Manifesto', 'C', 'medium', 'A referendum allows the electorate to vote directly on a specific proposal or issue.'],
    ['LITERATURE IN ENGLISH', 'A story in which animals are used as characters to teach a moral lesson is a?', 'Legend', 'Myth', 'Fable', 'Parable', 'C', 'medium', 'A fable is a short story, typically with animals as characters, conveying a moral.'],
    ['LITERATURE IN ENGLISH', 'The use of the same letter or sound at the beginning of adjacent words is?', 'Alliteration', 'Assonance', 'Consonance', 'Rhyme', 'A', 'medium', 'Alliteration is the repetition of initial consonant sounds in a sequence of words.'],
    ['LITERATURE IN ENGLISH', 'A character who provides a contrast to the protagonist is a?', 'Hero', 'Foil', 'Chorus', 'Antagonist', 'B', 'medium', 'A foil is a character who contrasts with another character to highlight particular qualities.'],
    ['LITERATURE IN ENGLISH', 'The perspective from which a story is told is the?', 'Plot', 'Setting', 'Point of view', 'Theme', 'C', 'medium', 'Point of view determines through whose eyes the audience experiences the story.'],
    ['LITERATURE IN ENGLISH', 'Exaggerated statements not meant to be taken literally are called?', 'Irony', 'Hyperbole', 'Metaphor', 'Simile', 'B', 'medium', 'Hyperbole is the use of exaggeration as a rhetorical device or figure of speech.'],
    ['ECONOMICS', 'The table that shows the various quantities of a good a consumer is willing to buy at different prices is a?', 'Supply schedule', 'Demand schedule', 'Production table', 'Utility table', 'B', 'medium', 'A demand schedule is a tabular representation of the relationship between price and quantity demanded.'],
    ['ECONOMICS', 'Inflation caused by an increase in the cost of production is?', 'Demand pull inflation', 'Cost push inflation', 'Hyperinflation', 'Deflation', 'B', 'hard', 'Cost push inflation occurs when overall prices rise due to increases in the cost of wages and raw materials.'],
    ['ECONOMICS', 'Which of the following is a direct tax?', 'Sales tax', 'Excise duty', 'Income tax', 'Value added tax', 'C', 'medium', 'Income tax is paid directly by an individual or organization to the government.'],
    ['ECONOMICS', 'The point where the demand curve and supply curve intersect is the?', 'Surplus point', 'Shortage point', 'Equilibrium point', 'Optimum point', 'C', 'medium', 'Equilibrium is the state in which market supply and demand balance each other.'],
    ['ECONOMICS', 'A production process that requires more machinery than labor is?', 'Labor intensive', 'Capital intensive', 'Land intensive', 'Management intensive', 'B', 'medium', 'Capital intensive refers to production that requires high levels of investment in equipment.'],
];

$inserted = 0;
$failed = 0;

echo "Inserting " . count($questions) . " questions...\n\n";

foreach ($questions as $idx => $q) {
    // Get or create subject
    $subjectName = $q[0];
    $result = $mysqli->query("SELECT id FROM subjects WHERE name = '" . $mysqli->real_escape_string($subjectName) . "'");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $subjectId = $row['id'];
    } else {
        $mysqli->query("INSERT INTO subjects (name, created_at, updated_at) VALUES ('" . $mysqli->real_escape_string($subjectName) . "', NOW(), NOW())");
        $subjectId = $mysqli->insert_id;
    }
    
    // Insert question
    $sql = "INSERT INTO questions (subject_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty_level, explanation, created_at, updated_at) 
            VALUES ($subjectId, '" . $mysqli->real_escape_string($q[1]) . "', '" . $mysqli->real_escape_string($q[2]) . "', '" . $mysqli->real_escape_string($q[3]) . "', '" . $mysqli->real_escape_string($q[4]) . "', '" . $mysqli->real_escape_string($q[5]) . "', '" . $q[6] . "', '" . $q[7] . "', '" . $mysqli->real_escape_string($q[8]) . "', NOW(), NOW())";
    
    if ($mysqli->query($sql)) {
        $inserted++;
        echo "✓ Question " . ($idx + 1) . " (" . $subjectName . ")\n";
    } else {
        $failed++;
        echo "✗ Question " . ($idx + 1) . ": " . $mysqli->error . "\n";
    }
}

echo "\n=== RESULTS ===\n";
echo "✓ Inserted: $inserted\n";
echo "✗ Failed: $failed\n";

// Verify count
$result = $mysqli->query("SELECT COUNT(*) as total FROM questions");
$row = $result->fetch_assoc();
echo "\nTotal questions in database: " . $row['total'] . "\n";

$mysqli->close();
