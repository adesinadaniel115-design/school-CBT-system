<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->integer('max_uses')->default(1);
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Track which students used which tokens
        Schema::create('exam_token_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_token_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_session_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('used_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_token_usage');
        Schema::dropIfExists('exam_tokens');
    }
};
