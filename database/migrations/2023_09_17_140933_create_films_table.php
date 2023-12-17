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
        Schema::create('films', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200);
            $table->string('slug', 255);
            $table->string('image', 255);
            $table->string('poster', 255);
            $table->string('limit_age', 255);
            $table->string('trailer', 255);
            $table->string('time', 20);
            $table->date('release_date');
            $table->date('end_date');
            $table->text('description');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes(); // add
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
