<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEPCEquipamientosServiciosTable extends Migration
{
    public function up()
    {
        Schema::create('proyecto.e_p_c_equipamientos_servicios', function (Blueprint $table) {

            $table->unsignedInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('proyecto.e_p_c_servicios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('equipamiento_id');
            $table->foreign('equipamiento_id')->references('id')->on('proyecto.e_p_c_equipamientos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->integer('cantidad');
        });
    }

    public function down()
    {
        Schema::dropIfExists('proyecto.e_p_c_equipamientos_servicios', function (Blueprint $table) {
            //
        });
    }
}
