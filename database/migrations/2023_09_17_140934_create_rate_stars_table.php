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
        Schema::create('rate_stars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('film_id');
            $table->text('comment')->nullable();
            $table->unsignedInteger('star_rating'); // Chỉ lấy số sao từ 1 đến 5
            
            $table->timestamps();
            $table->softDeletes(); //add
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_stars');
    }
};
