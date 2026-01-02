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
            $table->unsignedBigInteger('bracket_id')->nullable()->after('entry_weight_id');
            $table->foreign('bracket_id')->references('id')->on('uq_event_mate_brackets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uq_event_matches', function (Blueprint $table) {
            $table->dropForeign(['bracket_id']);
            $table->dropColumn('bracket_id');
        });
    }
};
