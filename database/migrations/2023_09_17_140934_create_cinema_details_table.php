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
        Schema::create('cinema_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cinema_id');
            $table->bigInteger('film_id');
            $table->timestamps();
            $table->softDeletes(); // add
            // Add foreign key constraints in a migration after creating the tables
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cinema_details');
    }
};
