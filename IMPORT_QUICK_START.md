# Question Import - Quick Start Guide

## 5-Minute Setup

### 1. Prepare Your CSV File

Create a file named `questions.csv` with this structure:

```csv
subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level,explanation
Mathematics,What is 2+2?,3,4,5,6,B,easy,2 plus 2 equals 4
Physics,SI unit of force?,Newton,Joule,Pascal,Watt,A,easy,Newton is the unit of force
Chemistry,Formula for water?,CO2,H2O,NaCl,O2,B,easy,H2O has 2 hydrogen and 1 oxygen
English,Past tense of go?,Goes,Going,Went,Gone,C,easy,The past tense is went
```

**Important:**
- Column headers must match exactly (case doesn't matter)
- No extra spaces around values
- Correct option must be A, B, C, or D
- Difficulty must be: easy, medium, or hard
- subject will auto-create if it doesn't exist

### 2. Upload the CSV

1. Log in as Admin
2. Go to **Admin Dashboard** (left sidebar)
3. Click **Questions** → **Import**
4. Click **Choose File** and select your CSV
5. Click **Import Questions**

### 3. View Results

The page shows:
- ✅ **Deleted Questions** - How many old questions were removed
- ✅ **Successfully Imported** - How many questions were added
- ⚠️ **Skipped (Errors)** - How many rows had validation errors
- ℹ️ **Total Processed** - Inserted + Skipped

If there are errors, they'll be listed with row numbers and descriptions.

### 4. Verify in Database

Go to **Admin Dashboard** → **Questions** to see all imported questions listed.

---

## Common Formats

### Minimal Format (only required fields)
```csv
subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level
Math,2+2?,A,B,C,D,B,easy
```

### With Explanation
```csv
subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level,explanation
Math,2+2?,3,4,5,6,B,easy,Because 2+2 equals 4
```

### With Images
```csv
subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level,image
Math,Diagram shows...?,3,4,5,6,B,easy,diagram_001.jpg
```

---

## Validation Checklist

Before uploading, verify:

- [ ] **Headers match** the required columns exactly
- [ ] **Correct option** is A, B, C, or D (uppercase)
- [ ] **Difficulty** is easy, medium, or hard (lowercase)
- [ ] **No empty cells** in required columns (subject, question_text, option_a-d, correct_option, difficulty_level)
- [ ] **Subject names** are spelled consistently (case-sensitive)
- [ ] **File format** is CSV (comma-separated, not semicolon)

---

## Creating CSV Files

### From Microsoft Excel
1. Open or create questions in Excel
2. **File** → **Save As**
3. Select **CSV (Comma delimited) (.csv)**
4. Save and upload

### From Google Sheets
1. Create/open sheet with questions
2. **File** → **Download** → **Comma Separated Values (.csv)**
3. Upload the downloaded file

### From Notepad
Create manually:
```
subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level
Math,How much is 2+2?,3,4,5,6,B,easy
Science,What is H2O?,Air,Water,Soil,Fire,B,easy
```
Save as `.csv` file (not `.txt`)

---

## What Happens on Import?

```
1. ❌ ALL existing questions are DELETED
   (Exam sessions, answers, and scores are preserved)

2. 📖 Your CSV is read and validated

3. ✅ Valid rows are inserted as new questions

4. ⚠️ Invalid rows are skipped with error messages

5. 📊 Summary shows what happened
```

---

## Safety Notes

✅ **Safe to run multiple times** - Each import is independent

✅ **Exam history preserved** - Old exams still exist even with deleted questions

✅ **Automatic subject creation** - No manual subject setup needed

✅ **Error recovery** - Failed rows don't prevent successful ones from importing

⚠️ **Deletes all questions** - Backup first if you want to keep old questions

---

## Sample File

A sample CSV file (`sample_questions.csv`) is included in the project:
1. Open `/sample_questions.csv`
2. Use as a template for your questions
3. Edit the content and upload

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| "Missing required column" | Check header row matches required columns |
| "correct_option must be A, B, C, or D" | Use uppercase letters only |
| "difficulty_level must be one of: easy, medium, hard" | Use lowercase, check spelling |
| "The question text field is required" | Ensure no empty cells in required columns |
| File won't upload | Save as `.csv` not `.xls` or `.xlsx` |
| No questions imported | Check "Skipped (Errors)" section for details |

---

## Next Steps

After importing questions:

1. **Admin Settings** - Configure exam duration and question counts
2. **Dashboard** - View questions by subject
3. **Students** - They can now take exams with these questions

---

Need help? Check `IMPORT_DOCUMENTATION.md` for complete details.
