<?php
$host = 'localhost';
$db = 'school_cbt_db';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Fixing subject ID numbering...\n\n";

// Disable foreign keys
$mysqli->query("SET FOREIGN_KEY_CHECKS=0");

// Get all subjects ordered by name and create mapping
$result = $mysqli->query("SELECT id, name FROM subjects ORDER BY name");
$subjects = [];
$mapping = []; // old_id => new_id
$newId = 1;

while ($row = $result->fetch_assoc()) {
    $mapping[$row['id']] = $newId;
    $subjects[] = ['old_id' => $row['id'], 'new_id' => $newId, 'name' => $row['name']];
    $newId++;
}

echo "Mapping old IDs to new IDs:\n";
foreach ($subjects as $s) {
    echo "  " . str_pad($s['old_id'], 2) . " → " . str_pad($s['new_id'], 2) . ": " . $s['name'] . "\n";
}
echo "\n";

// Update questions with new subject IDs
echo "Updating questions with new subject IDs...\n";
foreach ($mapping as $oldId => $newId) {
    $result = $mysqli->query("UPDATE questions SET subject_id = $newId WHERE subject_id = $oldId");
    $affected = $mysqli->affected_rows;
    if ($affected > 0) {
        echo "  ✓ Updated $affected questions from subject $oldId to $newId\n";
    }
}

// Delete all subjects
echo "\nDeleting old subjects...\n";
$mysqli->query("DELETE FROM subjects");
echo "  ✓ All subjects deleted\n";

// Re-insert subjects with sequential IDs
echo "\nInserting subjects with sequential IDs...\n";
foreach ($subjects as $s) {
    $name = $mysqli->real_escape_string($s['name']);
    $mysqli->query("INSERT INTO subjects (id, name, created_at, updated_at) VALUES ({$s['new_id']}, '$name', NOW(), NOW())");
    echo "  ✓ Inserted: ID {$s['new_id']} = {$s['name']}\n";
}

// Re-enable foreign keys
$mysqli->query("SET FOREIGN_KEY_CHECKS=1");

// Verify
echo "\n=== FINAL VERIFICATION ===\n";
$result = $mysqli->query("SELECT id, name FROM subjects ORDER BY id");
echo "Subjects (should be 1-10):\n";
while ($row = $result->fetch_assoc()) {
    echo "  ID " . str_pad($row['id'], 2) . ": " . $row['name'] . "\n";
}

$result = $mysqli->query("SELECT COUNT(*) as total FROM questions");
$row = $result->fetch_assoc();
echo "\nTotal questions: " . $row['total'] . "\n";

$result = $mysqli->query("SELECT s.id, s.name, COUNT(q.id) as count FROM subjects s LEFT JOIN questions q ON s.id = q.subject_id GROUP BY s.id, s.name ORDER BY s.id");
echo "\nQuestions per subject:\n";
while ($row = $result->fetch_assoc()) {
    echo "  ID {$row['id']}: {$row['name']} = {$row['count']} questions\n";
}

$mysqli->close();
echo "\n✅ Numbering fixed!\n";
