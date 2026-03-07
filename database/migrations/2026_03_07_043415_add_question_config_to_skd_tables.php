<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- skd_packages: add question config columns if not already there ---
        Schema::table('skd_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('skd_packages', 'twk_question_count'))
                $table->integer('twk_question_count')->default(30)->after('duration_minutes');
            if (!Schema::hasColumn('skd_packages', 'tiu_question_count'))
                $table->integer('tiu_question_count')->default(35)->after('twk_question_count');
            if (!Schema::hasColumn('skd_packages', 'tkp_question_count'))
                $table->integer('tkp_question_count')->default(45)->after('tiu_question_count');
            if (!Schema::hasColumn('skd_packages', 'twk_passing_grade'))
                $table->integer('twk_passing_grade')->default(65)->after('tkp_question_count');
            if (!Schema::hasColumn('skd_packages', 'tiu_passing_grade'))
                $table->integer('tiu_passing_grade')->default(80)->after('twk_passing_grade');
            if (!Schema::hasColumn('skd_packages', 'tkp_passing_grade'))
                $table->integer('tkp_passing_grade')->default(166)->after('tiu_passing_grade');
            if (!Schema::hasColumn('skd_packages', 'randomize_questions'))
                $table->boolean('randomize_questions')->default(true)->after('tkp_passing_grade');
        });

        // --- skd_sessions: add question_snapshot if not already there ---
        Schema::table('skd_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('skd_sessions', 'question_snapshot'))
                $table->json('question_snapshot')->nullable()->after('skd_package_id');
        });

        // --- skd_answers: migrate from old Question-based to PracticeQuestion-based ---
        if (Schema::hasColumn('skd_answers', 'question_id')) {
            // The unique index (skd_session_id, question_id) is the BACKING index for the
            // skd_answers_skd_session_id_foreign FK. MySQL error 1553 means we must drop the
            // FK constraint FIRST, then the unique index, all in one ALTER TABLE.
            \DB::statement('
                ALTER TABLE skd_answers
                    DROP FOREIGN KEY skd_answers_skd_session_id_foreign,
                    DROP INDEX skd_answers_skd_session_id_question_id_unique,
                    DROP INDEX skd_answers_question_id_foreign,
                    DROP INDEX skd_answers_option_id_foreign,
                    DROP COLUMN question_id,
                    DROP COLUMN option_id
            ');
            // Re-add the session FK with a regular index backing it
            Schema::table('skd_answers', function (Blueprint $table) {
                $table->foreign('skd_session_id')->references('id')->on('skd_sessions')->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('skd_answers', 'practice_question_id')) {
            Schema::table('skd_answers', function (Blueprint $table) {
                $table->foreignId('practice_question_id')
                    ->after('skd_session_id')
                    ->constrained('practice_questions')
                    ->cascadeOnDelete();
                $table->string('answer', 1)->nullable()->after('practice_question_id'); // A, B, C, D, E
                $table->boolean('is_doubtful')->default(false)->after('answer');
                $table->unique(['skd_session_id', 'practice_question_id']);
            });
        }

        // --- practice_questions: re-add difficulty and tags columns if rolled back ---
        Schema::table('practice_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('practice_questions', 'difficulty'))
                $table->string('difficulty')->default('medium')->after('explanation');
            if (!Schema::hasColumn('practice_questions', 'tags'))
                $table->json('tags')->nullable()->after('difficulty');
        });
    }

    public function down(): void
    {
        Schema::table('skd_packages', function (Blueprint $table) {
            $table->dropColumn([
                'twk_question_count', 'tiu_question_count', 'tkp_question_count',
                'twk_passing_grade', 'tiu_passing_grade', 'tkp_passing_grade',
                'randomize_questions',
            ]);
        });

        Schema::table('skd_sessions', function (Blueprint $table) {
            $table->dropColumn('question_snapshot');
        });

        if (Schema::hasColumn('skd_answers', 'practice_question_id')) {
            Schema::table('skd_answers', function (Blueprint $table) {
                $table->dropUnique(['skd_session_id', 'practice_question_id']);
                $table->dropForeign(['practice_question_id']);
                $table->dropColumn(['practice_question_id', 'answer', 'is_doubtful']);
            });
        }
    }
};
