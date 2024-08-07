<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEPCOtroservicioHasServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyecto.otroservicio_has_servicios', function (Blueprint $table) {
            $table->unsignedInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('proyecto.e_p_c_servicios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('otro_servicio_id');
            $table->foreign('otro_servicio_id')->references('id')->on('proyecto.e_p_c_otro_servicios')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->integer('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proyecto.otroservicio_has_servicios', function (Blueprint $table) {
            //
        });
    }
}
