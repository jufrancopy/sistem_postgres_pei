<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFodaCategoriasHasPerfilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.foda_categorias_has_perfil', function (Blueprint $table) {
            $table->unsignedInteger('perfil_id');
            $table->foreign('perfil_id')->references('id')->on('planificacion.foda_perfiles')
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
        Schema::dropIfExists('planificacion.foda_categorias_has_perfil', function (Blueprint $table) {
            //
        });
    }
}