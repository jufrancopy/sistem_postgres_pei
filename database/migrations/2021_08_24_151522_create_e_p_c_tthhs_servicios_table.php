<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEPCTthhsServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyecto.e_p_c_tthhs_servicios', function (Blueprint $table) {
            $table->unsignedInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('proyecto.e_p_c_servicios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('tthh_id');
            $table->foreign('tthh_id')->references('id')->on('proyecto.e_p_c_talento_humanos')
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
        Schema::dropIfExists('proyecto.e_p_c_tthhs_servicios', function (Blueprint $table) {
            //
        });
    }
}
