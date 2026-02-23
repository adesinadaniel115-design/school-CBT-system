<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE exam_sessions MODIFY started_at DATETIME NOT NULL");
        DB::statement("ALTER TABLE exam_sessions MODIFY completed_at DATETIME NULL");

        DB::statement("UPDATE exam_sessions SET started_at = created_at WHERE completed_at IS NOT NULL AND started_at = completed_at");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE exam_sessions MODIFY started_at TIMESTAMP NOT NULL");
        DB::statement("ALTER TABLE exam_sessions MODIFY completed_at TIMESTAMP NULL");
    }
};
