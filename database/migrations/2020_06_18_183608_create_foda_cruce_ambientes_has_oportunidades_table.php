<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFodaCruceAmbientesHasOportunidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.foda_cruce_ambientes_has_oportunidades', function (Blueprint $table) {
            $table->unsignedInteger('cruce_id');
            $table->foreign('cruce_id')->references('id')->on('planificacion.foda_analisis')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('planificacion.foda_categorias')
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
        Schema::dropIfExists('planificacion.foda_cruce_ambientes_has_oportunidades', function (Blueprint $table) {
            //
        });
    }
}