<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->string('type')->default('multiple_choice');
            $table->boolean('is_active')->default(true);
            $table->boolean('randomize_questions')->default(false);
            $table->integer('passing_score')->default(0);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->string('type')->default('choice');
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->integer('weight')->default(1);
            $table->timestamps();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('score')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('test_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamp('assigned_date')->useCurrent();
            $table->timestamp('deadline')->nullable();
            $table->timestamps();
        });

        Schema::create('test_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('option_id')->nullable()->constrained()->nullOnDelete();
            $table->text('essay_answer')->nullable();
            $table->integer('score')->default(0);
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            
            $table->unique(['test_session_id', 'question_id']);
        });

        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->foreignId('test_session_id')->constrained()->cascadeOnDelete();
            $table->integer('total_score')->default(0);
            $table->decimal('score_percentage', 5, 2)->default(0);
            $table->string('interpretation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('test_sessions');
        Schema::dropIfExists('test_assignments');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('tests');
    }
};
