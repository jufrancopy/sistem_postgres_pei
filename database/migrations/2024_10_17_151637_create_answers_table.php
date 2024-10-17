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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('participant_id');
            $table->foreign('participant_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->uuid('survey_id');
            $table->foreign('survey_id')->references('id')->on('surveys')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->jsonb('answer');
            $table->boolean('is_correct')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
};
