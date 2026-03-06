<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skd_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('skd_package_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skd_package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->enum('sub_test_type', ['twk', 'tiu', 'tkp']);
            $table->integer('passing_grade')->default(0);
            $table->integer('score_per_correct')->default(5);
            $table->timestamps();
        });

        Schema::create('skd_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skd_package_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('skd_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skd_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('option_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->unique(['skd_session_id', 'question_id']);
        });

        Schema::create('skd_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skd_package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skd_session_id')->constrained()->cascadeOnDelete();
            $table->integer('twk_score')->default(0);
            $table->integer('tiu_score')->default(0);
            $table->integer('tkp_score')->default(0);
            $table->integer('total_score')->default(0);
            $table->boolean('twk_passed')->default(false);
            $table->boolean('tiu_passed')->default(false);
            $table->boolean('tkp_passed')->default(false);
            $table->boolean('is_passed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skd_results');
        Schema::dropIfExists('skd_answers');
        Schema::dropIfExists('skd_sessions');
        Schema::dropIfExists('skd_package_tests');
        Schema::dropIfExists('skd_packages');
    }
};
