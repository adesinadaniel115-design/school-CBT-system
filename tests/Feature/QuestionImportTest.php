<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QuestionImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function import_replaces_questions_when_not_appending()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        // seed one question so we can verify it gets deleted
        $subject = Subject::create(['name' => 'Math']);
        Question::create([
            'subject_id' => $subject->id,
            'question_text' => 'Old question',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        $csv = implode("\n", [
            'subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level',
            'Math,New question,1,2,3,4,A,easy',
        ]);

        Storage::fake('local');
        $file = UploadedFile::fake()->createWithContent('questions.csv', $csv);

        $response = $this->post(route('admin.questions.import'), [
            'file' => $file,
            // no append
        ]);

        $response->assertRedirect(route('admin.questions.index'));
        $this->assertDatabaseMissing('questions', ['question_text' => 'Old question']);
        $this->assertDatabaseHas('questions', ['question_text' => 'New question']);
    }

    /** @test */
    public function import_appends_and_skips_duplicates_when_option_checked()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $subject = Subject::create(['name' => 'Math']);
        Question::create([
            'subject_id' => $subject->id,
            'question_text' => 'Existing question',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        $csv = implode("\n", [
            'subject,question_text,option_a,option_b,option_c,option_d,correct_option,difficulty_level',
            'Math,Existing question,1,2,3,4,A,easy', // duplicate should be skipped
            'Math,Another new one,1,2,3,4,B,medium',
        ]);

        Storage::fake('local');
        $file = UploadedFile::fake()->createWithContent('questions.csv', $csv);

        $response = $this->post(route('admin.questions.import'), [
            'file' => $file,
            'append' => '1',
        ]);

        $response->assertRedirect(route('admin.questions.index'));
        $this->assertDatabaseHas('questions', ['question_text' => 'Existing question']);
        $this->assertDatabaseHas('questions', ['question_text' => 'Another new one']);
        $this->assertEquals(2, Question::count());
    }
}
