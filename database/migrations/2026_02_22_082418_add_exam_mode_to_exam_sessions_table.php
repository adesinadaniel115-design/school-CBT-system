<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table): void {
            $table->enum('exam_mode', ['school', 'jamb'])->default('school')->after('subject_id');
            $table->unsignedInteger('duration_minutes')->after('total_questions');
        });
    }

    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table): void {
            $table->dropColumn(['exam_mode', 'duration_minutes']);
        });
    }
};
