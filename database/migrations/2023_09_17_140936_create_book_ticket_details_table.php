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
        Schema::create('book_ticket_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('book_ticket_id');
            $table->integer('time_id');
            $table->integer('food_id');
            $table->text('chair');
            $table->integer('quantity');
            $table->double('price', 8, 2);
            $table->timestamps();
            $table->softDeletes(); // add
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_ticket_details');
    }
};
