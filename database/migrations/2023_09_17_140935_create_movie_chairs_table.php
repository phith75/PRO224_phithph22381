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
        Schema::create('movie_chairs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->string('price');
            $table->tinyInteger('type');
            $table->bigInteger('id_time_detail');
<<<<<<< HEAD
            $table->timestamps();
            
=======

            $table->timestamps();
            $table->softDeletes(); // add
>>>>>>> 7bef63ef3b68826728f01362f36bc4e0909f94a9
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_chairs');
    }
};