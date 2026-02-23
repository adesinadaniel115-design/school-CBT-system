# Question Import System Documentation

## Overview

The Question Import system allows bulk importing of exam questions from CSV files with the following features:

1. **Automatic Question Deletion** - All existing questions are deleted before import (safe for exam sessions)
2. **Subject Auto-Creation** - If a subject doesn't exist, it's created automatically
3. **Validation** - Each row is validated before insertion; errors are logged and skipped
4. **Image Support** - Questions can include images stored locally or as base64 data
5. **Summary Report** - Detailed import statistics showing deleted, inserted, and skipped rows

## CSV File Format

### Required Columns (case-insensitive)

| Column | Type | Description |
|--------|------|-------------|
| `subject` | String | Subject name (auto-created if new) |
| `question_text` | String (required) | The question content |
| `option_a` | String (required) | Option A text |
| `option_b` | String (required) | Option B text |
| `option_c` | String (required) | Option C text |
| `option_d` | String (required) | Option D text |
| `correct_option` | String (required) | Correct answer: A, B, C, or D |
| `difficulty_level` | String (required) | Difficulty: easy, medium, or hard |

### Optional Columns

| Column | Type | Description |
|--------|------|-------------|
| `explanation` | String | Answer explanation shown to students |
| `image` | String | Image filename (relative to public/images/questions/) or base64 data |

## CSV Sample

```csv
subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level,explanation
Mathematics,What is 2+2?,3,4,5,6,B,easy,2 plus 2 equals 4
Physics,What is the SI unit of force?,Newton,Joule,Pascal,Watt,A,easy,The SI unit of force is Newton (N)
Chemistry,What is the chemical formula for water?,CO2,H2O,NaCl,O2,B,easy,Water is H2O - 2 hydrogen and 1 oxygen
English,What is the past tense of 'go'?,Goes,Going,Went,Gone,C,easy,The past tense of 'go' is 'went'
```

## Image Handling

### Option 1: Image Filenames
If your CSV contains image filenames, ensure the actual image files are stored at:
```
public/images/questions/filename.jpg
```

Then in the CSV:
```csv
subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level,image
Mathematics,Diagram question...,A,B,C,D,A,medium,diagram_1.jpg
```

### Option 2: Base64 Images
Encode images as base64 data URLs directly in the CSV:
```csv
subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level,image
Mathematics,Base64 question...,A,B,C,D,A,medium,"data:image/png;base64,iVBORw0KGgoAAAANS..."
```

## Validation Rules

Each row must pass the following validation:

- **subject**: Required, non-empty string
- **question_text**: Required, non-empty string
- **option_a, option_b, option_c, option_d**: Required, non-empty strings
- **correct_option**: Required, must be A, B, C, or D (case-insensitive)
- **difficulty_level**: Required, must be one of: easy, medium, hard
- **explanation**: Optional
- **image**: Optional, must be valid file path or base64 data

## Import Process

### Step 1: Upload CSV

1. Navigate to **Admin Dashboard → Questions → Import**
2. Select your CSV file
3. Click **Import Questions**

### Step 2: Import Execution

The system will:

1. **Delete** all existing questions from the database
   - Exam sessions and answers are NOT deleted (preserved for records)
   - Question links from exams become null but don't break the system

2. **Process** each row:
   - Validate required fields
   - Check correct_option format (A-D)
   - Verify difficulty_level (easy/medium/hard)
   - Get or create subject by name

3. **Import** valid questions:
   - Create or link to subject
   - Store question with all fields
   - Save image if provided
   - Insert into database

4. **Skip** invalid rows:
   - Log validation errors
   - Display errors to user
   - Continue processing remaining rows

### Step 3: View Summary

After import completes:

| Metric | Meaning |
|--------|---------|
| Deleted Questions | Number of questions removed from database |
| Successfully Imported | Number of questions inserted |
| Skipped (Errors) | Number of rows with validation errors |
| Total Processed | Inserted + Skipped |

## Safety Guarantees

✅ **Exam Sessions Protected**
- Deleting questions does NOT delete exam session records
- Student exam history is completely preserved
- Previous exam scores and answers remain intact

✅ **Transaction Safety**
- Entire import runs in a database transaction
- If any critical error occurs, all changes are rolled back
- Either all rows import or none do (no partial imports)

✅ **Subject Integrity**
- Existing subjects are never modified
- New subjects are only created if needed
- Subject names are case-sensitive during matching

✅ **Detailed Error Logging**
- Every validation error is captured and displayed
- Line numbers shown for easy CSV correction
- Error messages explain exactly what's wrong

## Database Schema

The `questions` table includes:

```sql
CREATE TABLE questions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    subject_id BIGINT NOT NULL FOREIGN KEY,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option ENUM('A','B','C','D') NOT NULL,
    explanation TEXT NULLABLE,
    difficulty_level ENUM('easy','medium','hard') DEFAULT 'medium',
    image VARCHAR(255) NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (subject_id, difficulty_level)
);
```

## Controller Methods

### `showImportForm()`
Displays the import page with upload form and format guide.

### `import(Request $request)`
Main import logic:
- Validates file upload
- Reads CSV file
- Checks headers
- Deletes existing questions
- Processes and validates each row
- Returns summary with errors

### Private Helper Methods

- `readCsvFile($file)` - Parse CSV and return array of rows
- `normalizeRow($row)` - Trim whitespace and handle nulls
- `handleImageUpload($imageInput, $subjectId, $lineNumber)` - Store image files
- `validateQuestion($request)` - Validation rules for single questions

## Error Handling

### Common Errors

| Error | Solution |
|-------|----------|
| Missing required column | Check CSV header row matches required columns |
| Invalid correct_option | Must be exactly A, B, C, or D |
| Invalid difficulty_level | Must be easy, medium, or hard (lowercase) |
| Empty required field | Ensure all cells in required columns have values |
| File read error | Try different CSV format or software (Excel, Google Sheets, etc) |

### Validation Error Example

```
Row 5: The correct option field is required.
Row 7: The difficulty level must be one of: easy, medium, hard.
Row 12: The question text field is required.
```

## Performance Considerations

- **Large files** (1000+ rows) may take a few seconds to process
- **Image handling** is slower with base64 encoded data
- Import runs in a **database transaction** for safety
- Indexes on `(subject_id, difficulty_level)` help with filtering

## Troubleshooting

### Q: Questions were deleted but not imported
**A:** Check for validation errors displayed on the import page. Fix the CSV and re-import.

### Q: Some subjects weren't created
**A:** Check if they already exist in the database. Subject names are case-sensitive.

### Q: Images aren't showing
**A:** Ensure images are stored at `public/images/questions/` or use valid base64 data.

### Q: Import is slow
**A:** Large CSV files (5000+ rows) may take time. Try importing in smaller batches.

### Q: I need to keep old questions
**A:** Backup questions first before importing. Use the question export/download feature if available.

## API Usage (For Developers)

### Import via Form

```php
// Upload a CSV file
$response = $this->post('/admin/questions-import', [
    'file' => UploadedFile::fake()->createWithContent(
        'questions.csv',
        $csvContent
    )
]);

// Response will redirect with session data:
// session('import_summary') - Contains deleted_count, inserted_count, skipped_count
// session('import_errors') - If any rows failed validation
// session('status') - Success message if all rows imported
```

### Direct Model Import (Advanced)

```php
// Create questions directly
Question::create([
    'subject_id' => $subject->id,
    'question_text' => 'Question here',
    'option_a' => 'A answer',
    'option_b' => 'B answer',
    'option_c' => 'C answer',
    'option_d' => 'D answer',
    'correct_option' => 'A',
    'difficulty_level' => 'easy',
    'explanation' => 'Explanation here',
    'image' => 'images/questions/file.jpg', // optional
]);
```

## Changelog

### Version 1.0 (Current)
- CSV import with validation
- Auto-create subjects
- Image support (filenames + base64)
- Transaction safety
- Detailed error reporting
- Import summary statistics

## Support Files

Sample CSV file: `sample_questions.csv` in project root

To use the sample:
1. Copy `sample_questions.csv`
2. Go to Admin → Questions → Import
3. Select the sample file
4. Click Import
5. View the populated database
