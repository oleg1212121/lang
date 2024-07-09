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
        Schema::create('english_context_meanings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('english_word_id');
            $table->unsignedBigInteger('context_meaning_id');
            $table->timestamps();
        });

        Schema::table('english_context_meanings', function (Blueprint $table) {
            $table->foreign('english_word_id')->references('id')->on('english_words');
            $table->foreign('context_meaning_id')->references('id')->on('contextual_meanings');
            $table->unique(['english_word_id', 'context_meaning_id'], "english_word_id_meaning_id_unique");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_context_meanings');
    }
};
