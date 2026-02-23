<?php
$host = 'localhost';
$db = 'school_cbt_db';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$questions = [
    // Chemistry - Subject ID 2
    ['Chemistry', 'The constituent of air that supports combustion is?', 'Nitrogen', 'Oxygen', 'Carbon dioxide', 'Argon', 'B', 'medium', 'Oxygen is chemically required for the process of burning or combustion.', 2],
    ['Chemistry', 'Which of the following is a physical change?', 'Burning of wood', 'Rusting of iron', 'Melting of ice', 'Souring of milk', 'C', 'medium', 'Melting is a change of state where no new chemical substance is formed.', 2],
    ['Chemistry', 'What is the common name for the compound Calcium Carbonate?', 'Quicklime', 'Slaked lime', 'Limestone', 'Gypsum', 'C', 'medium', 'Limestone is a sedimentary rock composed largely of the mineral calcium carbonate.', 2],
    ['Chemistry', 'The separation technique used in the production of table salt from seawater is?', 'Distillation', 'Evaporation', 'Filtration', 'Sublimation', 'B', 'medium', 'Evaporation removes the liquid solvent (water) to leave behind the solid solute (salt).', 2],
    ['Chemistry', 'Which of the following is a member of the Alkyne series?', 'Methane', 'Ethene', 'Ethyne', 'Propane', 'C', 'hard', 'Alkynes are unsaturated hydrocarbons characterized by a carbon to carbon triple bond.', 2],
    
    // Christian Religious Study - Subject ID 3
    ['Christian Religious Study', 'Which book of the Bible is considered the Gospel that emphasizes Jesus as the eternal Word of God?', 'Matthew', 'Mark', 'Luke', 'John', 'D', 'medium', 'The Gospel of John opens with the famous passage identifying Jesus as the Word (Logos) that was with God from the beginning.', 3],
    ['Christian Religious Study', 'The Last Supper was instituted by Jesus to commemorate which event?', 'His birth', 'His baptism', 'The Passover and His coming sacrifice', 'His resurrection', 'C', 'medium', 'Jesus instituted the Last Supper as a memorial of the Passover and as a symbol of His sacrifice for humanity.', 3],
    ['Christian Religious Study', 'According to Christian teaching, what is the primary purpose of the Ten Commandments?', 'To establish civil law', 'To reveal Gods moral standards and humanity\'s sinfulness', 'To guarantee prosperity', 'To replace the need for faith', 'B', 'medium', 'The Ten Commandments reveal God\'s moral character and standards, and demonstrate humanity\'s inability to achieve righteousness through works alone.', 3],
    ['Christian Religious Study', 'The doctrine of justification by faith is most prominently taught in which book of the New Testament?', 'Hebrews', 'Romans', 'Revelation', 'Corinthians', 'B', 'medium', 'Paul\'s letter to the Romans extensively explains the doctrine that justification (being made right with God) comes through faith in Jesus Christ, not by works.', 3],
    ['Christian Religious Study', 'Which apostle is traditionally credited with writing the most epistles (letters) in the New Testament?', 'Peter', 'John', 'Paul', 'James', 'C', 'medium', 'The Apostle Paul authored approximately 13-14 letters (epistles) that form a significant portion of the New Testament, including Romans, Corinthians, and Galatians.', 3],
];

echo "Restoring Chemistry and Christian Religious Study questions...\n\n";

$inserted = 0;
foreach ($questions as $q) {
    $sql = "INSERT INTO questions (subject_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty_level, explanation, created_at, updated_at) 
            VALUES ({$q[9]}, '" . $mysqli->real_escape_string($q[1]) . "', '" . $mysqli->real_escape_string($q[2]) . "', '" . $mysqli->real_escape_string($q[3]) . "', '" . $mysqli->real_escape_string($q[4]) . "', '" . $mysqli->real_escape_string($q[5]) . "', '" . $q[6] . "', '" . $q[7] . "', '" . $mysqli->real_escape_string($q[8]) . "', NOW(), NOW())";
    
    if ($mysqli->query($sql)) {
        $inserted++;
        $subject = $q[0];
        echo "✓ Restored question for $subject\n";
    } else {
        echo "✗ Error: " . $mysqli->error . "\n";
    }
}

echo "\n=== FINAL CHECK ===\n";
$result = $mysqli->query("SELECT s.id, s.name, COUNT(q.id) as q_count FROM subjects s LEFT JOIN questions q ON s.id = q.subject_id GROUP BY s.id ORDER BY s.id");
while ($row = $result->fetch_assoc()) {
    echo "ID {$row['id']}: {$row['name']} = {$row['q_count']} questions\n";
}

$result = $mysqli->query("SELECT COUNT(*) as total FROM questions");
$row = $result->fetch_assoc();
echo "\nTotal questions: " . $row['total'] . "\n";

$mysqli->close();
echo "\n✅ Fixed!\n";
