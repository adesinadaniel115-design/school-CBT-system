<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('school_questions')->nullable()->after('price');
            $table->integer('jamb_questions_per_subject')->nullable()->after('school_questions');
            $table->integer('jamb_english_questions')->nullable()->after('jamb_questions_per_subject');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['school_questions', 'jamb_questions_per_subject', 'jamb_english_questions']);
        });
    }
};
