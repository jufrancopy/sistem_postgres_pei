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
            $table->uuid('perfil_id');
            $table->foreign('perfil_id')->references('id')->on('planificacion.foda_perfiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('planificacion.foda_models')
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
        Schema::dropIfExists('planificacion.foda_categorias_has_perfil', function (Blueprint $table) {
            //
        });
    }
}
