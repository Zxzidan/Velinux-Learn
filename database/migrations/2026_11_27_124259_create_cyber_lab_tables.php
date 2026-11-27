<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lab_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('difficulty')->default('beginner');
            $table->text('scenario');
            $table->longText('instructions');
            $table->string('target_host')->nullable();
            $table->text('terminal_prompt')->nullable();
            $table->string('flag');
            $table->unsignedInteger('xp_reward')->default(100);
            $table->unsignedSmallInteger('time_limit_minutes')->default(45);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'category']);
        });

        Schema::create('lab_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_challenge_id')->constrained()->cascadeOnDelete();
            $table->string('container_id');
            $table->string('status')->default('running');
            $table->timestamp('started_at');
            $table->timestamp('expires_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_challenge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_session_id')->nullable()->constrained()->nullOnDelete();
            $table->string('submitted_flag');
            $table->text('notes')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedSmallInteger('score')->default(0);
            $table->string('status')->default('pending');
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'lab_challenge_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('lab_sessions');
        Schema::dropIfExists('lab_challenges');
    }
};
