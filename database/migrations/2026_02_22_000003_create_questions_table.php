<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->enum('correct_option', ['A', 'B', 'C', 'D']);
            $table->text('explanation')->nullable();
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->default('medium');
            $table->timestamps();

            $table->index(['subject_id', 'difficulty_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
