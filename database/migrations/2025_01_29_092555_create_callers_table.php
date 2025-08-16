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
        Schema::create('callers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userId')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('status')->default('1');
            $table->string('image')->nullable();
            $table->string('password');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('callers');
    }
};
