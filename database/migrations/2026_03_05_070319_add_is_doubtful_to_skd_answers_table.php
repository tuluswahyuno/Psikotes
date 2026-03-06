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
        Schema::table('skd_answers', function (Blueprint $table) {
            $table->boolean('is_doubtful')->default(false)->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('skd_answers', function (Blueprint $table) {
            $table->dropColumn('is_doubtful');
        });
    }
};
