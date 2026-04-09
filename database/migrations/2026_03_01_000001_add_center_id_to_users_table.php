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
                $table->foreignId('center_id')->nullable()->constrained('centers')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'center_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['center_id']);
                $table->dropColumn('center_id');
            });
        }
    }
};
