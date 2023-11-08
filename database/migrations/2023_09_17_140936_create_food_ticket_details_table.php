<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('food_ticket_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('book_ticket_id')->unsigned();
            $table->integer('food_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_ticket_details');
    }
};
