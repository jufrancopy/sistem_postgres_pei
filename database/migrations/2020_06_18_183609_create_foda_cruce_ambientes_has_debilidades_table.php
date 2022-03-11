<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFodaCruceAmbientesHasDebilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.foda_cruce_ambientes_has_debilidades', function (Blueprint $table) {
            $table->unsignedInteger('cruce_id');
            $table->foreign('cruce_id')->references('id')->on('planificacion.foda_cruce_ambientes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('debilidad_id');
            $table->foreign('debilidad_id')->references('id')->on('planificacion.foda_aspectos')
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
        Schema::dropIfExists('planificacion.foda_cruce_ambientes_has_debilidades', function (Blueprint $table) {
            //
        });
    }
}
