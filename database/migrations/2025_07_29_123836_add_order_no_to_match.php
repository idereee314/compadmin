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
            $table->integer('order_no')->notNullable()->default(0)->comment('Order Number for Match');
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
