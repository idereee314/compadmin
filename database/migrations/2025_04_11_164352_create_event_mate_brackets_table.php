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
        Schema::create('uq_event_mate_brackets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->index()->comment('Event ID');
            $table->unsignedBigInteger('day_id')->index()->comment('Day ID');
            $table->unsignedBigInteger('mat_id')->index()->comment('Mate ID');
            $table->unsignedBigInteger('entry_id')->index()->comment('Bracket ID');
            $table->unsignedBigInteger('entry_belt_id')->index()->comment('Belt ID');
            $table->unsignedBigInteger('entry_age_id')->index()->comment('Age ID');
            $table->unsignedBigInteger('entry_weight_id')->index()->comment('Weight ID');
            $table->integer('total_hour')->comment('Total Hour');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_mate_brackets');
    }
};
