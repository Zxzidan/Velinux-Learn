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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('quiz_type')->default('cyber_quiz');
            $table->unsignedInteger('time_limit_seconds')->default(900);
            $table->unsignedTinyInteger('passing_score')->default(70);
            $table->unsignedInteger('xp_reward')->default(50);
            $table->boolean('is_daily')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'is_daily']);
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->string('question_type')->default('multiple_choice');
            $table->text('prompt');
            $table->text('code_snippet')->nullable();
            $table->json('options')->nullable();
            $table->text('correct_answer');
            $table->text('rubric')->nullable();
            $table->unsignedSmallInteger('points')->default(10);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['quiz_id', 'sort_order']);
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('score')->default(0);
            $table->unsignedSmallInteger('max_score')->default(100);
            $table->string('status')->default('pending');
            $table->timestamp('ai_evaluated_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('answer_text');
            $table->boolean('is_correct')->nullable();
            $table->unsignedSmallInteger('score_awarded')->default(0);
            $table->timestamps();
        });

        Schema::create('ai_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('answer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('recommended_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->text('summary');
            $table->text('strengths')->nullable();
            $table->text('improvements')->nullable();
            $table->string('recommended_topic')->nullable();
            $table->decimal('confidence_score', 4, 2)->nullable();
            $table->string('model_name')->nullable();
            $table->text('mentor_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_feedback');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
    }
};
