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
        Schema::create('uq_country_abbrevation', function (Blueprint $table) {
            $table->string('abbrevation')->index();
            $table->string('token');
        });
    }
};
