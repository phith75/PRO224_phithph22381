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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('id_card'); // mã thẻ 
            $table->integer('card_class')->comment('1: Value1, 2: Value2'); // hạng thẻ 1 là  hạng thường 2 là hạng vip
            $table->date('activation_date'); // thời gian tạo 
            $table->integer('total_spending'); // tổng chi tiêu
            $table->integer('accumulated_points'); // điểm tích lũy
            $table->integer('points_used'); // số điểm đã sử dụng
            $table->integer('usable_points'); // số điểm có thể dùng
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
