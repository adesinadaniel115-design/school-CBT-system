<?php
$host = 'localhost';
$db = 'school_cbt_db';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// New subjects and their questions
$subjectsData = [
    'Commerce' => [
        ['Commerce', 'Which of the following is a primary function of commerce in an economy?', 'Production of goods', 'Exchange of goods and services', 'Employment creation', 'Technology innovation', 'B', 'medium', 'Commerce involves the buying, selling, and exchange of goods and services between producers and consumers.'],
        ['Commerce', 'The process of making goods available to consumers through various channels is known as?', 'Distribution', 'Production', 'Warehousing', 'Packaging', 'A', 'medium', 'Distribution is the process of transporting goods from producers to retailers and finally to end consumers.'],
        ['Commerce', 'Which of the following best describes a monopoly in business?', 'Many sellers competing freely', 'Two firms controlling the market', 'One firm controlling the entire supply of a product', 'Several firms with equal market share', 'C', 'medium', 'A monopoly exists when a single firm has exclusive control over the production and sale of a particular good or service.'],
        ['Commerce', 'What is the primary purpose of market segmentation?', 'To reduce production costs', 'To divide the market into distinct groups with specific needs', 'To eliminate competition', 'To increase prices', 'B', 'medium', 'Market segmentation allows businesses to identify and target specific groups of consumers with tailored products and marketing strategies.'],
        ['Commerce', 'Which of the following is a disadvantage of sole proprietorship?', 'Unlimited liability', 'Easy to establish', 'Full ownership of profits', 'Complete control over decisions', 'A', 'medium', 'In a sole proprietorship, the owner has unlimited liability, meaning personal assets can be used to settle business debts.'],
    ],
    'Christian Religious Study' => [
        ['Christian Religious Study', 'Which book of the Bible is considered the Gospel that emphasizes Jesus as the eternal Word of God?', 'Matthew', 'Mark', 'Luke', 'John', 'D', 'medium', 'The Gospel of John opens with the famous passage identifying Jesus as the Word (Logos) that was with God from the beginning.'],
        ['Christian Religious Study', 'The Last Supper was instituted by Jesus to commemorate which event?', 'His birth', 'His baptism', 'The Passover and His coming sacrifice', 'His resurrection', 'C', 'medium', 'Jesus instituted the Last Supper as a memorial of the Passover and as a symbol of His sacrifice for humanity.'],
        ['Christian Religious Study', 'According to Christian teaching, what is the primary purpose of the Ten Commandments?', 'To establish civil law', 'To reveal Gods moral standards and humanity\'s sinfulness', 'To guarantee prosperity', 'To replace the need for faith', 'B', 'medium', 'The Ten Commandments reveal God\'s moral character and standards, and demonstrate humanity\'s inability to achieve righteousness through works alone.'],
        ['Christian Religious Study', 'The doctrine of justification by faith is most prominently taught in which book of the New Testament?', 'Hebrews', 'Romans', 'Revelation', 'Corinthians', 'B', 'medium', 'Paul\'s letter to the Romans extensively explains the doctrine that justification (being made right with God) comes through faith in Jesus Christ, not by works.'],
        ['Christian Religious Study', 'Which apostle is traditionally credited with writing the most epistles (letters) in the New Testament?', 'Peter', 'John', 'Paul', 'James', 'C', 'medium', 'The Apostle Paul authored approximately 13-14 letters (epistles) that form a significant portion of the New Testament, including Romans, Corinthians, and Galatians.'],
    ],
];

$inserted = 0;
$failed = 0;

echo "Inserting 10 questions for 2 new subjects...\n\n";

foreach ($subjectsData as $subjectName => $questions) {
    // Get or create subject
    $result = $mysqli->query("SELECT id FROM subjects WHERE name = '" . $mysqli->real_escape_string($subjectName) . "'");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $subjectId = $row['id'];
        echo "Using existing subject: $subjectName (ID: $subjectId)\n";
    } else {
        $mysqli->query("INSERT INTO subjects (name, created_at, updated_at) VALUES ('" . $mysqli->real_escape_string($subjectName) . "', NOW(), NOW())");
        $subjectId = $mysqli->insert_id;
        echo "Created new subject: $subjectName (ID: $subjectId)\n";
    }
    
    // Insert questions for this subject
    foreach ($questions as $idx => $q) {
        $sql = "INSERT INTO questions (subject_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty_level, explanation, created_at, updated_at) 
                VALUES ($subjectId, '" . $mysqli->real_escape_string($q[1]) . "', '" . $mysqli->real_escape_string($q[2]) . "', '" . $mysqli->real_escape_string($q[3]) . "', '" . $mysqli->real_escape_string($q[4]) . "', '" . $mysqli->real_escape_string($q[5]) . "', '" . $q[6] . "', '" . $q[7] . "', '" . $mysqli->real_escape_string($q[8]) . "', NOW(), NOW())";
        
        if ($mysqli->query($sql)) {
            $inserted++;
            echo "  ✓ Question " . ($idx + 1) . "\n";
        } else {
            $failed++;
            echo "  ✗ Question " . ($idx + 1) . ": " . $mysqli->error . "\n";
        }
    }
    echo "\n";
}

// Verify final counts
echo "=== FINAL RESULTS ===\n";
echo "✓ Inserted: $inserted\n";
echo "✗ Failed: $failed\n\n";

$result = $mysqli->query("SELECT COUNT(*) as total_subjects FROM subjects");
$row = $result->fetch_assoc();
echo "Total Subjects: " . $row['total_subjects'] . "\n";

$result = $mysqli->query("SELECT COUNT(*) as total_questions FROM questions");
$row = $result->fetch_assoc();
echo "Total Questions: " . $row['total_questions'] . "\n\n";

// Show subject breakdown
echo "Questions per subject:\n";
$result = $mysqli->query("SELECT s.name, COUNT(q.id) as count FROM subjects s LEFT JOIN questions q ON s.id = q.subject_id GROUP BY s.id, s.name ORDER BY s.name");
while ($row = $result->fetch_assoc()) {
    echo "  - " . $row['name'] . ": " . $row['count'] . " questions\n";
}

$mysqli->close();
