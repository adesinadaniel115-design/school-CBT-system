<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $subjectId = $request->input('subject_id');

        $query = Question::with('subject')->orderByDesc('id');
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $questions = $query->paginate(10)->withQueryString();

        return view('admin.questions.index', compact('questions', 'subjects', 'subjectId'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();

        return view('admin.questions.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $this->validateQuestion($request);

        Question::create($data);

        return redirect()->route('admin.questions.index')
            ->with('status', 'Question created successfully.');
    }

    public function edit(Question $question)
    {
        $subjects = Subject::orderBy('name')->get();

        return view('admin.questions.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $data = $this->validateQuestion($request);

        $question->update($data);

        // Redirect back to the subject detail page if coming from there
        $subjectId = $request->input('subject_id') ?? $question->subject_id;
        
        if ($subjectId) {
            return redirect()->route('admin.subjects.show', $subjectId)
                ->with('status', 'Question updated successfully.');
        }

        return redirect()->back()
            ->with('status', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('status', 'Question deleted successfully.');
    }

    public function showImportForm()
    {
        $subjects = Subject::orderBy('name')->get();

        return view('admin.questions.import', compact('subjects'));
    }

    public function downloadTemplate()
    {
        $headers = [
            'subject',
            'question_text',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_option',
            'difficulty_level',
            'explanation',
            'image',
        ];

        $sampleRows = [
            [
                'Mathematics',
                'What is the value of 2 + 2?',
                '3',
                '4',
                '5',
                '6',
                'B',
                'easy',
                '2 plus 2 equals 4. This is basic addition.',
                '',
            ],
            [
                'Physics',
                'What is the SI unit of force?',
                'Newton',
                'Joule',
                'Pascal',
                'Watt',
                'A',
                'easy',
                'The Newton (N) is the SI unit of force. 1 N = 1 kg⋅m⋅s⁻²',
                '',
            ],
            [
                'Chemistry',
                'What is the chemical formula for water?',
                'CO2',
                'H2O',
                'NaCl',
                'O2',
                'B',
                'easy',
                'Water is composed of 2 hydrogen atoms and 1 oxygen atom (H2O).',
                '',
            ],
            [
                'English Language',
                'Which of the following is a noun?',
                'Run',
                'Beautiful',
                'Quickly',
                'Dog',
                'D',
                'medium',
                'A noun is a person, place, or thing. "Dog" is a noun.',
                '',
            ],
            [
                'Biology',
                'What is the basic unit of life?',
                'Atom',
                'Cell',
                'Tissue',
                'Organ',
                'B',
                '',
                'The cell is the basic unit of all living organisms.',
                '',
            ],
        ];

        // Create CSV content
        $csvContent = $this->generateCsvContent($headers, $sampleRows);

        // Create a temporary file
        $fileName = 'questions_template_' . date('Y-m-d') . '.csv';

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function generateCsvContent($headers, $rows)
    {
        $output = fopen('php://memory', 'r+');

        // Write headers
        fputcsv($output, $headers);

        // Write sample rows
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        // Get content
        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return $content;
    }

    public function import(Request $request)
    {
        // Validate file
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $file = $request->file('file');

        try {
            // Read CSV file
            $rows = $this->readCsvFile($file);

            if (empty($rows)) {
                return back()->withErrors(['file' => 'File contains no data.']);
            }

            // Validate headers
            $firstRow = $rows[0];
            $requiredColumns = ['subject', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option', 'difficulty_level'];
            $headers = array_map('strtolower', array_keys($firstRow));
            foreach ($requiredColumns as $column) {
                if (!in_array($column, $headers)) {
                    return back()->withErrors(['file' => "Missing required column: {$column}"]);
                }
            }

            // Delete existing questions and start fresh
            $deletedCount = Question::count();
            
            // Disable foreign key checks for truncate, then re-enable
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Question::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $summary = DB::transaction(function () use ($rows) {
                $inserted = 0;
                $skipped = 0;
                $errors = [];

                foreach ($rows as $index => $row) {
                    $lineNumber = $index + 2; // +2 because of header row and 1-based indexing

                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        $skipped++;
                        continue;
                    }

                    // Create normalized row data
                    $normalizedRow = $this->normalizeRow($row);

                    // Validate row
                    $validator = Validator::make($normalizedRow, [
                        'subject' => ['required', 'string'],
                        'question_text' => ['required', 'string'],
                        'option_a' => ['required', 'string'],
                        'option_b' => ['required', 'string'],
                        'option_c' => ['required', 'string'],
                        'option_d' => ['required', 'string'],
                        'correct_option' => ['required', 'in:A,B,C,D'],
                        'difficulty_level' => ['nullable', 'in:easy,medium,hard'],
                        'image' => ['nullable', 'string'],
                    ]);

                    if ($validator->fails()) {
                        $skipped++;
                        $errors[$lineNumber] = $validator->errors()->all();
                        continue;
                    }

                    try {
                        // Get or create subject
                        $subject = Subject::firstOrCreate(
                            ['name' => $normalizedRow['subject']],
                            ['name' => $normalizedRow['subject']]
                        );

                        // Handle image if provided
                        $imagePath = null;
                        if (!empty($normalizedRow['image'])) {
                            $imagePath = $this->handleImageUpload($normalizedRow['image'], $subject->id, $lineNumber);
                        }

                        // Create question
                        Question::create([
                            'subject_id' => $subject->id,
                            'question_text' => $normalizedRow['question_text'],
                            'option_a' => $normalizedRow['option_a'],
                            'option_b' => $normalizedRow['option_b'],
                            'option_c' => $normalizedRow['option_c'],
                            'option_d' => $normalizedRow['option_d'],
                            'correct_option' => strtoupper($normalizedRow['correct_option']),
                            'explanation' => $normalizedRow['explanation'] ?? null,
                            'difficulty_level' => $normalizedRow['difficulty_level'],
                            'image' => $imagePath,
                        ]);

                        $inserted++;
                    } catch (\Exception $e) {
                        $skipped++;
                        $errors[$lineNumber] = [$e->getMessage()];
                    }
                }

                return [
                    'inserted' => $inserted,
                    'skipped' => $skipped,
                    'errors' => $errors,
                ];
            });

            // Prepare response
            $response = [
                'deleted_count' => $deletedCount,
                'inserted_count' => $summary['inserted'],
                'skipped_count' => $summary['skipped'],
            ];

            if (!empty($summary['errors'])) {
                return back()->with('import_summary', $response)->with('import_errors', $summary['errors']);
            }

            return redirect()->route('admin.questions.index')
                ->with('import_summary', $response)
                ->with('status', 'Questions imported successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error processing file: ' . $e->getMessage()]);
        }
    }

    /**
     * Read CSV file and return rows as array of associative arrays
     */
    private function readCsvFile($file): array
    {
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \Exception('Unable to read the file.');
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            throw new \Exception('File header is missing.');
        }

        // Normalize headers to lowercase
        $header = array_map(function ($value) {
            return strtolower(trim($value));
        }, $header);

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            // Skip completely empty rows
            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }

            // Handle mismatched column counts - take only as many values as headers
            $row = array_slice($row, 0, count($header));
            // Pad with nulls if row has fewer columns than header
            $row = array_pad($row, count($header), null);
            
            $rows[] = array_combine($header, $row);
        }

        fclose($handle);
        return $rows;
    }

    /**
     * Normalize row data - trim whitespace and handle nulls
     */
    private function normalizeRow(array $row): array
    {
        return array_map(function ($value) {
            if ($value === null || $value === '') {
                return null;
            }
            return is_string($value) ? trim($value) : $value;
        }, $row);
    }

    /**
     * Handle image upload from file path or base64
     */
    private function handleImageUpload($imageInput, $subjectId, $lineNumber): ?string
    {
        try {
            // Check if it's a file path (relative to public/images/questions)
            if (file_exists(public_path('images/questions/' . $imageInput))) {
                return 'questions/' . $imageInput;
            }

            // Check if it's a base64 encoded image
            if (strpos($imageInput, 'base64,') !== false) {
                [, $imageData] = explode(',', $imageInput);
                $imageData = base64_decode($imageData);
                $imageName = 'question_' . $subjectId . '_' . time() . '_' . uniqid() . '.png';
                Storage::disk('public')->put('questions/' . $imageName, $imageData);
                return 'questions/' . $imageName;
            }

            // If it's just a filename, assume it's relative to public/images/questions
            return 'questions/' . $imageInput;

        } catch (\Exception $e) {
            // Log error but don't fail the import
            \Log::warning("Failed to process image for subject {$subjectId} at line {$lineNumber}: " . $e->getMessage());
            return null;
        }
    }

    private function validateQuestion(Request $request): array
    {
        return $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'in:A,B,C,D'],
            'explanation' => ['nullable', 'string'],
            'difficulty_level' => ['nullable', 'in:easy,medium,hard'],
        ]);
    }
}
