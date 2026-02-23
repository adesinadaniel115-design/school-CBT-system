<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table): void {
            $table->boolean('hidden_by_student')->default(false)->after('completed_at');
            $table->index(['student_id', 'hidden_by_student', 'completed_at']);
        });
    }

    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table): void {
            $table->dropIndex(['student_id', 'hidden_by_student', 'completed_at']);
            $table->dropColumn('hidden_by_student');
        });
    }
};
