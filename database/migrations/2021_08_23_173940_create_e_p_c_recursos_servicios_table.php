<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEPCRecursosServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyecto.e_p_c_recursos_servicios', function (Blueprint $table) {

            $table->unsignedInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('proyecto.e_p_c_servicios')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 
            
            $table->unsignedInteger('detail_equipamiento_id');
            $table->foreign('detail_equipamiento_id')->references('id')->on('proyecto.e_p_c_equipamientos')
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
        Schema::dropIfExists('proyecto.e_p_c_recursos_servicios', function (Blueprint $table) {
            //
        });
    }
}
