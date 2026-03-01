<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'center_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Add center_id without foreign key constraint to avoid dependency issues
                $table->unsignedBigInteger('center_id')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['center_id']);
            $table->dropColumn('center_id');
        });
    }
};
