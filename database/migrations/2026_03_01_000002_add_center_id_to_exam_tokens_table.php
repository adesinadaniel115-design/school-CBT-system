<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_tokens', function (Blueprint $table) {
            $table->foreignId('center_id')->nullable()->constrained('centers')->after('notes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('exam_tokens', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['center_id']);
            $table->dropColumn('center_id');
        });
    }
};
