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
            $table->bigInteger('category_id')->unsigned();
            $table->string('name');
            $table->string('lastname');
            $table->integer('identification')->unique();
            $table->string('email')->unique();
            $table->string('country');
            $table->string('address');
            $table->string('mobile');                        
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('2023');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
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
