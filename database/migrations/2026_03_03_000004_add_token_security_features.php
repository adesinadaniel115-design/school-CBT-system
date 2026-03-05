<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add security tracking to exam_tokens table
        Schema::table('exam_tokens', function (Blueprint $table) {
            // Bind token to first user who uses it (prevents sharing)
            $table->foreignId('bound_user_id')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
            
            // Flag for sharing detected
            $table->boolean('sharing_detected')->default(false)->after('bound_user_id');
            
            // Require tokens to be used from same IP/device (optional strictness)
            $table->ipAddress('first_used_ip')->nullable()->after('sharing_detected');
            $table->string('first_used_device')->nullable()->after('first_used_ip');
        });

        // Track all usage attempts per user per token
        Schema::table('exam_token_usage', function (Blueprint $table) {
            $table->ipAddress('ip_address')->nullable()->after('used_at');
            $table->string('user_agent')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('exam_token_usage', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent']);
        });

        Schema::table('exam_tokens', function (Blueprint $table) {
            $table->dropColumn(['bound_user_id', 'sharing_detected', 'first_used_ip', 'first_used_device']);
        });
    }
};
