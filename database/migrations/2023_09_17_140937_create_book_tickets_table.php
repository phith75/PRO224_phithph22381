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
        Schema::create('book_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('id_time_detail');
            $table->tinyInteger('payment');
            $table->integer('amount');
            $table->double('price', 8, 2);
            $table->tinyInteger('status')->default(0);
            $table->integer('id_code')->nullable();
            $table->timestamps();
            $table->softDeletes(); // add
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_tickets');
    }
};
