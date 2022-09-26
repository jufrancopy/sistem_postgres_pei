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
        Schema::create('estadistica.formulario_formulario_has_variables', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('formulario_id');
            $table->foreign('formulario_id')->references('id')->on('estadistica.formulario_formularios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('variable_id');
            $table->foreign('variable_id')->references('id')->on('estadistica.formulario_variables')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 
                
            $table->integer('value')->nullable();   

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
        Schema::dropIfExists('estadistica.formulario_formulario_has_variables');
    }
}
