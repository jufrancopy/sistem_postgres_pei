<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers_has_questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->json('answers')->nullable(); // Campo para almacenar respuestas en formato JSON
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers_has_questions');
    }
};
