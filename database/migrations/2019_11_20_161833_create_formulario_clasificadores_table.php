<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormularioClasificadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estadistica.formulario_clasificadores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('clasificador');

            $table->unsignedInteger('clasificador_id')->nullable();
            $table->foreign('clasificador_id')->references('id')->on('estadistica.formulario_clasificadores')
                    ->onDelete('cascade')
                    ->onUpdate('cascade'); 

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('estadistica.formulario_clasificadores');
    }
}
