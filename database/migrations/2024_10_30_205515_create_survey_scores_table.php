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
        Schema::create('survey_scores', function (Blueprint $table) {
            $table->id(); // Esto crea un campo id autoincremental
            $table->uuid('survey_id');
            $table->foreign('survey_id')->references('id')->on('surveys')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('participant_id');
            $table->foreign('participant_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('score')->default(0);

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
        Schema::dropIfExists('survey_scores');
    }
};
