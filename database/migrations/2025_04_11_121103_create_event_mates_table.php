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
        Schema::create('uq_event_mates', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('day_id');
            $table->integer('mate_no');
            $table->integer('total_hour');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uq_event_mates');
    }
};
