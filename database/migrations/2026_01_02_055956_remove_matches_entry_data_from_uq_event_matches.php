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
            $table->dropColumn(['entry_age_id', 'entry_belt_id', 'entry_weight_id', 'entry_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uq_event_matches', function (Blueprint $table) {
            //
        });
    }
};
