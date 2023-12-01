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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('id_cinema')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->tinyInteger('role')->default(0);
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('date_of_birth')->nullable();
            $table->integer('coin')->default(0);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // delete
            //quên password
            $table->string('reset_password_token')->nullable();
            $table->timestamp('reset_password_token_expiry')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
