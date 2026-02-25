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
        Schema::table('uq_event_matches', function (Blueprint $table) {
            $table->integer('red_score')->default(0);
            $table->integer('blue_score')->default(0);
            $table->integer('red_advantage')->default(0);
            $table->integer('blue_advantage')->default(0);
            $table->integer('red_penalty')->default(0);
            $table->integer('blue_penalty')->default(0);
            $table->string('win_method')->nullable();
            $table->integer('red_match_points')->default(0);
            $table->integer('blue_match_points')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uq_event_matches', function (Blueprint $table) {
            $table->dropColumn([
                'red_score', 'blue_score',
                'red_advantage', 'blue_advantage',
                'red_penalty', 'blue_penalty',
                'win_method',
                'red_match_points', 'blue_match_points',
            ]);
        });
    }
};