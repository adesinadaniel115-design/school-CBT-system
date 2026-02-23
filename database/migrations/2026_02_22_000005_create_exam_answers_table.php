<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_answers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('exam_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->enum('selected_option', ['A', 'B', 'C', 'D'])->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->unique(['exam_session_id', 'question_id']);
            $table->index(['exam_session_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
