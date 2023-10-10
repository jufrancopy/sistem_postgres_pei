<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFodaCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('planificacion.foda_categorias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('ambiente');
            
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('modelo_id');
            $table->foreign('modelo_id')->references('id')->on('planificacion.foda_models')
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
        Schema::dropIfExists('planificacion.foda_categorias', function (Blueprint $table) {
            //
        });
    }
}
