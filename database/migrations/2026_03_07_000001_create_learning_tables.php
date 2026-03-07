<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // TWK, TIU, TKP
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('sub_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['section_id', 'slug']);
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_topic_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('practice_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_topic_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->json('options');         // ["A" => "...", "B" => "...", ...]
            $table->string('correct_answer');
            $table->text('explanation')->nullable();
            $table->timestamps();
        });

        Schema::create('user_material_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'material_id']);
        });

        Schema::create('practice_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sub_topic_id')->constrained()->cascadeOnDelete();
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->decimal('score', 5, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('practice_attempts')->cascadeOnDelete();
            $table->foreignId('practice_question_id')->constrained()->cascadeOnDelete();
            $table->string('user_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->unique(['attempt_id', 'practice_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_answers');
        Schema::dropIfExists('practice_attempts');
        Schema::dropIfExists('user_material_progress');
        Schema::dropIfExists('practice_questions');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('sub_topics');
        Schema::dropIfExists('sections');
    }
};
