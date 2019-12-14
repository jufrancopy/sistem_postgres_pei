<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormularioFomularios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globales.formulario_formularios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('formulario');

            $table->unsignedInteger('dependencia_emisor_id')->nullable();
            $table->foreign('dependencia_emisor_id')->references('id')->on('globales.organigramas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('dependencia_receptor_id')->nullable();
            $table->foreign('dependencia_receptor_id')->references('id')->on('globales.organigramas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('globales.formulario_formularios');
    }
}
