<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Question;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create subjects
        $english = Subject::create(['name' => 'English']);
        $math = Subject::create(['name' => 'Mathematics']);
        $physics = Subject::create(['name' => 'Physics']);
        $chemistry = Subject::create(['name' => 'Chemistry']);
        $biology = Subject::create(['name' => 'Biology']);

        $this->command->info('Created 5 subjects');

        // Create 60 questions for English (required for JAMB)
        for ($i = 1; $i <= 60; $i++) {
            Question::create([
                'subject_id' => $english->id,
                'question_text' => "What is the meaning of 'example' in English Question {$i}?",
                'option_a' => 'A sample or illustration',
                'option_b' => 'A complicated task',
                'option_c' => 'A person who teaches',
                'option_d' => 'None of the above',
                'correct_option' => 'A'
            ]);
        }

        $this->command->info('Created 60 English questions');

        // Create 40 questions for each other subject
        $subjects = [$math, $physics, $chemistry, $biology];
        
        foreach ($subjects as $subject) {
            for ($i = 1; $i <= 40; $i++) {
                Question::create([
                    'subject_id' => $subject->id,
                    'question_text' => "Sample {$subject->name} Question {$i}: What is the correct answer?",
                    'option_a' => 'This is option A',
                    'option_b' => 'This is option B',
                    'option_c' => 'This is option C',
                    'option_d' => 'This is option D',
                    'correct_option' => ['A', 'B', 'C', 'D'][array_rand(['A', 'B', 'C', 'D'])]
                ]);
            }
            
            $this->command->info("Created 40 {$subject->name} questions");
        }

        $this->command->info('Test data seeding completed successfully!');
    }
}
