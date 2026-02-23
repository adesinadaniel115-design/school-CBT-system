<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_subject_scores', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('exam_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('correct_count')->default(0);
            $table->decimal('score_over_100', 5, 2)->default(0);
            $table->timestamps();

            $table->index(['exam_session_id', 'subject_id']);
            $table->unique(['exam_session_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_subject_scores');
    }
};
