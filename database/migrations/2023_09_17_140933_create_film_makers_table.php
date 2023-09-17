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
        Schema::create('film_makers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');
            $table->string('name', 100);
            $table->string('image', 255);
            $table->string('as', 255);
            $table->bigInteger('film_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_makers');
    }
};
