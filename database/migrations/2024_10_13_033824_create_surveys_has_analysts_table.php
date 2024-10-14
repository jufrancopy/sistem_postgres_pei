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
        Schema::create('surveys_has_analysts', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('survey_id');
            $table->foreign('survey_id')->references('id')->on('surveys')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('analyst_id');
            $table->foreign('analyst_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveys_has_analysts');
    }
};
