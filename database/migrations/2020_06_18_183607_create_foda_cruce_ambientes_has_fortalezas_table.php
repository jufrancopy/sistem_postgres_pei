<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFodaCruceAmbientesHasFortalezasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.foda_cruce_ambientes_has_fortalezas', function (Blueprint $table) {
            $table->unsignedInteger('cruce_id');
            $table->foreign('cruce_id')->references('id')->on('planificacion.foda_analisis')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('fortaleza_id');
            $table->foreign('fortaleza_id')->references('id')->on('planificacion.foda_aspectos')
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
        Schema::dropIfExists('planificacion.foda_cruce_ambientes_has_fortalezas', function (Blueprint $table) {
            //
        });
    }
}
