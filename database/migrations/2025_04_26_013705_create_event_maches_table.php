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
        Schema::create('uq_event_matches', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('entry_id');
            $table->integer('entry_age_id');
            $table->integer('entry_belt_id');
            $table->integer('entry_weight_id');
            $table->integer('reg_one_id')->nullable();
            $table->integer('reg_two_id')->nullable();
            $table->integer('reg_win_id')->nullable();
            $table->integer('previes_mate_id1')->nullable()->comment('Previous Mate ID');
            $table->integer('previes_mate_id2')->nullable()->comment('Previous Mate ID');
            $table->time('end_time')->nullable()->comment('End Time');
            $table->enum('status', ['C', 'P', 'A'])->default('P')->comment('Status: C=Complete, P=Pending, A=Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_maches');
    }
};
