<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeiDependencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.pei_dependencias', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('pei_id');
            $table->foreign('pei_id')->references('id')->on('planificacion.peis')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('dependency_id');
            $table->foreign('dependency_id')->references('id')->on('organigramas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('planificacion.pei_dependencias');
    }
}
