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
        Schema::create('book_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('id_time_detail')->unsigned();
            $table->tinyInteger('payment')->nullable();
            $table->integer('amount')->nullable();
            $table->string('time', 255)->nullable();
            $table->integer('id_staff_check')->default(0);
            $table->string('id_code')->nullable();
            $table->integer('status')->default(0);
            $table->integer('discount_voucher')->default(0);
            $table->timestamps();
            $table->softDeletes();
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
