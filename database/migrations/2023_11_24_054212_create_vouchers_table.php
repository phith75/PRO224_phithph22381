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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->timestamp('start_time');
            $table->dateTime('end_time');
            $table->integer('usage_limit');
            $table->integer('price_voucher');
            $table->string('description');
            $table->integer('remaining_limit')->nullable();
            $table->integer('limit')->comment('1: Value1, 2: Value2');
            $table->integer('percent')->comment('0%->100%');
            $table->integer('minimum_amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
