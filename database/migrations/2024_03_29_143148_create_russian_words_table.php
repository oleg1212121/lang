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
        Schema::create('russian_words', function (Blueprint $table) {
            $table->id();
            $table->string("word",255)->nullable(false);
            $table->string("meaning",500)->nullable();
            $table->integer("frequency")->nullable(false)->default(11111);
            $table->integer("known")->nullable(false)->default(1);
            $table->integer("level")->nullable(false)->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('russian_words');
    }
};
