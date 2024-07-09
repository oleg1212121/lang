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
        Schema::create('english_russian_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('english_word_id');
            $table->unsignedBigInteger('russian_word_id');
            $table->timestamps();
        });

        Schema::table('english_russian_relations', function (Blueprint $table) {
            $table->foreign('english_word_id')->references('id')->on('english_words');
            $table->foreign('russian_word_id')->references('id')->on('russian_words');
            $table->unique(['english_word_id', 'russian_word_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('english_russian_relations', function (Blueprint $table) {
//            $table->dropIndex(['english_word_id', 'russian_word_id']);
//            $table->dropForeign('english_words_english_word_id_foreign');
//            $table->dropForeign('russian_words_russian_word_id_foreign');
        });

        Schema::dropIfExists('english_russian_relations');
    }
};
