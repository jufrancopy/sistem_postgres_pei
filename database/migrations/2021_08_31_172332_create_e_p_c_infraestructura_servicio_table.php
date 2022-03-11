<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEPCInfraestructuraServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyecto.e_p_c_infraestructura_servicio', function (Blueprint $table) {
            $table->unsignedInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('proyecto.e_p_c_servicios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('infraestructura_id');
            $table->foreign('infraestructura_id')->references('id')->on('proyecto.e_p_c_infraestructuras')
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
        Schema::dropIfExists('proyecto.e_p_c_infraestructura_servicio', function (Blueprint $table) {
            //
        });
    }
}
