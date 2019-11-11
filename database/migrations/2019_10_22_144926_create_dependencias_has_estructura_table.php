<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependenciasHasEstructuraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globales.dependencias_has_estructuras', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('estructura_id');
            $table->foreign('estructura_id')->references('id')->on('globales.estructuras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('dependency_id');
            $table->foreign('dependency_id')->references('id')->on('globales.organigramas')
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
        Schema::dropIfExists('globales.dependencias_has_estructuras');
    }
}
