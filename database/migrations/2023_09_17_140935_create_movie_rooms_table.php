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
        Schema::create('movie_rooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_cinema');
            $table->string('name', 100);
            $table->timestamps();
            $table->bigInteger('id_cinema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_rooms');
    }
};