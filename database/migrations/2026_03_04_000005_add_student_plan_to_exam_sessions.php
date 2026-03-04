<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            // store the student_plan record that was consumed when the session was started
            $table->foreignId('student_plan_id')->nullable()
                  ->constrained('student_plans')
                  ->nullOnDelete()
                  ->after('subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropForeign(['student_plan_id']);
            $table->dropColumn('student_plan_id');
        });
    }
};
