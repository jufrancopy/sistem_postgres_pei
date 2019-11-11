<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormularioFormularioHasVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globales.formulario_formulario_has_variables', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('fomulario_id');
            $table->foreign('fomulario_id')->references('id')->on('globales.formulario_formularios')
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
        Schema::dropIfExists('globales.formulario_formulario_has_variables');
    }
}
