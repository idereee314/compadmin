<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Recreate the uq_event_matches table (dropped accidentally).
     * This combines the original create + all subsequent alter migrations:
     *   - 2025_04_26_013705_create_event_maches_table
     *   - 2025_07_29_123836_add_order_no_to_match
     *   - 2026_01_02_055632_add_bracket_id_to_uq_event_matches_table
     *   - 2026_01_02_055956_remove_matches_entry_data_from_uq_event_matches
     *   - 2026_01_14_123614_add_matches_double_loser
     *   - 2026_02_25_100000_add_scores_to_uq_event_matches
     */
    public function up(): void
    {
        Schema::create('uq_event_matches', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('reg_one_id')->nullable();
            $table->integer('reg_two_id')->nullable();
            $table->integer('reg_win_id')->nullable();
            $table->unsignedBigInteger('bracket_id')->nullable();
            $table->integer('previes_mate_id1')->nullable()->comment('Previous Mate ID');
            $table->integer('previes_mate_id2')->nullable()->comment('Previous Mate ID');
            $table->time('end_time')->nullable()->comment('End Time');
            $table->enum('status', ['C', 'P', 'A'])->default('P')->comment('Status: C=Complete, P=Pending, A=Active');
            $table->integer('order_no')->default(0)->comment('Order Number for Match');
            $table->boolean('is_double_loser')->default(false);
            $table->integer('red_score')->default(0);
            $table->integer('blue_score')->default(0);
            $table->integer('red_advantage')->default(0);
            $table->integer('blue_advantage')->default(0);
            $table->integer('red_penalty')->default(0);
            $table->integer('blue_penalty')->default(0);
            $table->string('win_method')->nullable();
            $table->integer('red_match_points')->default(0);
            $table->integer('blue_match_points')->default(0);
            $table->timestamps();

            $table->foreign('bracket_id')->references('id')->on('uq_event_mate_brackets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uq_event_matches');
    }
};